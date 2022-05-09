<?php

/*
 * This file is part of the composer package buepro/typo3-wise.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Buepro\Wise\Api;

use Buepro\Wise\Service\ApiService;
use Buepro\Wise\Utility\ApiUtility;
use GuzzleHttp\RequestOptions;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Site\Entity\Site;

class Client
{
    protected string $apiTokenKey = '';
    protected int $profileId = 0;
    protected string $privateKey = '';
    protected string $baseUrl = 'https://api.transferwise.com';
    protected Site $site;
    private RequestFactory $requestFactory;

    public function __construct(RequestFactory $requestFactory)
    {
        $this->requestFactory = $requestFactory;
    }

    public function initialize(Site $site, int $profileId = 0): self
    {
        $this->site = $site;
        $config = $site->getAttribute('wise');
        $this->apiTokenKey = $config['apiTokenKey'] ?? '';
        $this->profileId = $profileId;
        $this->privateKey = (new ApiService())->getPrivateKey($site);
        return $this;
    }

    /**
     * @link https://api-docs.transferwise.com/#balance-account-get-balance-account-statement
     */
    public function getBalanceAccountStatement(int $intervalStart, int $intervalEnd): array
    {
        $accounts = $this->getMultiCurrencyAccounts();
        if (($borderlessAccountId = (string)($accounts['id'] ?? '')) === '') {
            return [];
        }
        return ApiUtility::getArrayFromJson($this->getResponseContent(sprintf(
            '%s/v3/profiles/%d/borderless-accounts/%s/statement.%s?currency=%s&intervalStart=%s&intervalEnd=%s',
            $this->baseUrl,
            $this->profileId,
            $borderlessAccountId,
            'json',
            'EUR',
            ApiUtility::getDateTimeStringFromTimestamp($intervalStart - 3600),
            ApiUtility::getDateTimeStringFromTimestamp($intervalEnd + 3600)
        )));
    }

    /**
     * @link https://api-docs.transferwise.com/#multi-currency-account-get-multi-currency-accounts
     */
    public function getMultiCurrencyAccounts(): array
    {
        return ApiUtility::getArrayFromJson($this->getResponseContent(sprintf(
            '%s/v4/profiles/%d/multi-currency-account',
            $this->baseUrl,
            $this->profileId
        )));
    }

    private function getResponseContent(string $url): string
    {
        $options = [
            RequestOptions::HTTP_ERRORS => false,
            'headers' => ['Authorization' => 'Bearer ' . $this->apiTokenKey],
        ];
        $response = $this->requestFactory->request($url, 'GET', $options);
        if ($response->getStatusCode() === 200) {
            return $response->getBody()->getContents();
        }
        if (
            $response->getStatusCode() === 403 &&
            ($oneTimeToken = $response->getHeaderLine('x-2fa-approval')) !== '' &&
            ($privateKey = openssl_pkey_get_private($this->privateKey)) !== false
        ) {
            // Try again with signed one time token
            openssl_sign($oneTimeToken, $xSignature, $privateKey, OPENSSL_ALGO_SHA256);
            if (PHP_VERSION_ID < 80000) {
                openssl_free_key($privateKey);
            }
            $xSignature= base64_encode($xSignature);
            $options['headers']['x-2fa-approval'] = $oneTimeToken;
            $options['headers']['X-Signature'] = $xSignature;
            $response = $this->requestFactory->request($url, 'GET', $options);
            if ($response->getStatusCode() === 200) {
                return $response->getBody()->getContents();
            }
        }
        return '';
    }
}
