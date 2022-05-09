<?php

/*
 * This file is part of the composer package buepro/typo3-wise.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

defined('TYPO3') or die('Access denied.');

return [
    'ctrl' => [
        'title' => 'LLL:EXT:wise/Resources/Private/Language/locallang_db.xlf:tx_wise_domain_model_event',
        'label' => 'delivery_id',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'default_sortby' => 'uid DESC',
        'searchFields' => 'amount, post_transaction_balance_amount',
        'iconfile' => 'EXT:wise/Resources/Public/Icons/Event.svg'
    ],
    'types' => [
        '1' => ['showitem' => 'delivery_id, id, profile_id, currency, amount, post_transaction_balance_amount, occurred_at'],
    ],
    'columns' => [
        'delivery_id' => [
            'label' => 'LLL:EXT:wise/Resources/Private/Language/locallang_db.xlf:tx_wise_domain_model_event.delivery_id',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'readOnly' => 1,
            ],
        ],
        'id' => [
            'label' => 'LLL:EXT:wise/Resources/Private/Language/locallang_db.xlf:tx_wise_domain_model_event.id',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'readOnly' => 1,
            ],
        ],
        'profile_id' => [
            'label' => 'LLL:EXT:wise/Resources/Private/Language/locallang_db.xlf:tx_wise_domain_model_event.profile_id',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'readOnly' => 1,
            ],
        ],
        'amount' => [
            'label' => 'LLL:EXT:wise/Resources/Private/Language/locallang_db.xlf:tx_wise_domain_model_event.amount',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'readOnly' => 1,
            ],
        ],
        'currency' => [
            'label' => 'LLL:EXT:wise/Resources/Private/Language/locallang_db.xlf:tx_wise_domain_model_event.currency',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'readOnly' => 1,
            ],
        ],
        'post_transaction_balance_amount' => [
            'label' => 'LLL:EXT:wise/Resources/Private/Language/locallang_db.xlf:tx_wise_domain_model_event.post_transaction_balance_amount',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'readOnly' => 1,
            ],
        ],
        'occurred_at' => [
            'label' => 'LLL:EXT:wise/Resources/Private/Language/locallang_db.xlf:tx_wise_domain_model_event.occurred_at',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'readOnly' => 1,
            ],
        ],
    ],
];
