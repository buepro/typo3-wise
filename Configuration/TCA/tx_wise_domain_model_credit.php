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
        'title' => 'LLL:EXT:wise/Resources/Private/Language/locallang_db.xlf:tx_wise_domain_model_credit',
        'label' => 'reference_number',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'default_sortby' => 'event ASC, uid DESC',
        'searchFields' => 'reference_number, amount_value, running_balance_value',
        'iconfile' => 'EXT:wise/Resources/Public/Icons/Credit.svg',
    ],
    'palettes' => [
        'reference' => ['showitem' => 'reference_number, date'],
        'amount' => ['showitem' => 'amount_value, amount_currency'],
        'fees' => ['showitem' => 'total_fees_value, total_fees_currency'],
        'details' => [
            'label' => 'LLL:EXT:wise/Resources/Private/Language/locallang_db.xlf:tx_wise_domain_model_credit.palette.details',
            'showitem' => 'details_type, details_description, --linebreak--, details_sender_name, ' .
                'details_sender_account, --linebreak--, details_payment_reference, exchange_details',
        ],
        'running_balance' => ['showitem' => 'running_balance_value, running_balance_currency'],
    ],
    'types' => [
        '1' => ['showitem' => '--palette--;;reference, --palette--;;amount, --palette--;;fees, ' .
            '--palette--;;details, --palette--;;running_balance, event'],
    ],
    'columns' => [
        'reference_number' => [
            'label' => 'LLL:EXT:wise/Resources/Private/Language/locallang_db.xlf:tx_wise_domain_model_credit.reference_number',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'readOnly' => 1,
            ],
        ],
        'date' => [
            'label' => 'LLL:EXT:wise/Resources/Private/Language/locallang_db.xlf:tx_wise_domain_model_credit.date',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'readOnly' => 1,
            ],
        ],
        'amount_value' => [
            'label' => 'LLL:EXT:wise/Resources/Private/Language/locallang_db.xlf:tx_wise_domain_model_credit.amount_value',
            'config' => [
                'type' => 'input',
                'eval' => 'double2, trim',
                'readOnly' => 1,
            ],
        ],
        'amount_currency' => [
            'label' => 'LLL:EXT:wise/Resources/Private/Language/locallang_db.xlf:tx_wise_domain_model_credit.amount_currency',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'readOnly' => 1,
            ],
        ],
        'total_fees_value' => [
            'label' => 'LLL:EXT:wise/Resources/Private/Language/locallang_db.xlf:tx_wise_domain_model_credit.total_fees_value',
            'config' => [
                'type' => 'input',
                'eval' => 'double2, trim',
                'readOnly' => 1,
            ],
        ],
        'total_fees_currency' => [
            'label' => 'LLL:EXT:wise/Resources/Private/Language/locallang_db.xlf:tx_wise_domain_model_credit.total_fees_currency',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'readOnly' => 1,
            ],
        ],
        'details_type' => [
            'label' => 'LLL:EXT:wise/Resources/Private/Language/locallang_db.xlf:tx_wise_domain_model_credit.details_type',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'readOnly' => 1,
            ],
        ],
        'details_description' => [
            'label' => 'LLL:EXT:wise/Resources/Private/Language/locallang_db.xlf:tx_wise_domain_model_credit.details_description',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'readOnly' => 1,
            ],
        ],
        'details_sender_name' => [
            'label' => 'LLL:EXT:wise/Resources/Private/Language/locallang_db.xlf:tx_wise_domain_model_credit.details_sender_name',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'readOnly' => 1,
            ],
        ],
        'details_sender_account' => [
            'label' => 'LLL:EXT:wise/Resources/Private/Language/locallang_db.xlf:tx_wise_domain_model_credit.details_sender_account',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'readOnly' => 1,
            ],
        ],
        'details_payment_reference' => [
            'label' => 'LLL:EXT:wise/Resources/Private/Language/locallang_db.xlf:tx_wise_domain_model_credit.details_payment_reference',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'readOnly' => 1,
            ],
        ],
        'exchange_details' => [
            'label' => 'LLL:EXT:wise/Resources/Private/Language/locallang_db.xlf:tx_wise_domain_model_credit.exchange_details',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'readOnly' => 1,
            ],
        ],
        'running_balance_value' => [
            'label' => 'LLL:EXT:wise/Resources/Private/Language/locallang_db.xlf:tx_wise_domain_model_credit.running_balance_value',
            'config' => [
                'type' => 'input',
                'eval' => 'double2, trim',
                'readOnly' => 1,
            ],
        ],
        'running_balance_currency' => [
            'label' => 'LLL:EXT:wise/Resources/Private/Language/locallang_db.xlf:tx_wise_domain_model_credit.running_balance_currency',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'readOnly' => 1,
            ],
        ],
        'event' => [
            'label' => 'LLL:EXT:wise/Resources/Private/Language/locallang_db.xlf:tx_wise_domain_model_credit.event',
            'config' => [
                'type' => 'input',
                'readOnly' => 1,
            ],
        ],
    ],
];
