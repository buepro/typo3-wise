CREATE TABLE tx_wise_domain_model_event (
	delivery_id varchar(100) DEFAULT '' NOT NULL,
	id int(11) unsigned DEFAULT '0' NOT NULL,
	profile_id int(11) unsigned DEFAULT '0' NOT NULL,
	amount double(11,2) DEFAULT '0.00' NOT NULL,
	currency varchar(10) DEFAULT '' NOT NULL,
	post_transaction_balance_amount double(11,2) DEFAULT '0.00' NOT NULL,
	occurred_at varchar(100) DEFAULT '' NOT NULL,
	occurred_at_processed int(11) DEFAULT '0' NOT NULL
);

CREATE TABLE tx_wise_domain_model_credit (
	reference_number varchar(100) DEFAULT '' NOT NULL,
	date varchar(100) DEFAULT '' NOT NULL,
	date_processed int(11) DEFAULT '0' NOT NULL,
	amount_value double(11,2) DEFAULT '0.00' NOT NULL,
	amount_currency varchar(10) DEFAULT '' NOT NULL,
	total_fees_value double(11,2) DEFAULT '0.00' NOT NULL,
	total_fees_currency varchar(10) DEFAULT '' NOT NULL,
	details_type varchar(100) DEFAULT '' NOT NULL,
	details_description varchar(255) DEFAULT '' NOT NULL,
	details_sender_name varchar(100) DEFAULT '' NOT NULL,
	details_sender_account varchar(100) DEFAULT '' NOT NULL,
	details_payment_reference varchar(100) DEFAULT '' NOT NULL,
	exchange_details varchar(100) DEFAULT '' NOT NULL,
	running_balance_value double(11,2) DEFAULT '0.00' NOT NULL,
	running_balance_currency varchar(10) DEFAULT '' NOT NULL,
	event int(11) unsigned DEFAULT '0'
);
