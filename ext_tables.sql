CREATE TABLE fe_users (
	tx_newsletter_bounce int(11) DEFAULT '0' NOT NULL
);

CREATE TABLE be_users (
	tx_newsletter_bounce int(11) DEFAULT '0' NOT NULL
);

CREATE TABLE tx_newsletter_domain_model_newsletter (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	
	planned_time int(11) unsigned DEFAULT '0' NOT NULL,
	begin_time int(11) unsigned DEFAULT '0' NOT NULL,
	end_time int(11) unsigned DEFAULT '0' NOT NULL,
	recipient_list int(11) unsigned DEFAULT '0',
	is_test tinyint(1) unsigned DEFAULT '0' NOT NULL,
	repetition int(11) DEFAULT '0' NOT NULL,
	sender_name varchar(255) DEFAULT '' NOT NULL,
	sender_email varchar(255) DEFAULT '' NOT NULL,
	plain_converter varchar(255) DEFAULT 'Tx_Newsletter_Domain_Model_PlainConverter_Builtin' NOT NULL,
	attachments varchar(255) DEFAULT '' NOT NULL,
	inject_open_spy tinyint(1) unsigned DEFAULT '0' NOT NULL,
	inject_links_spy tinyint(1) unsigned DEFAULT '0' NOT NULL,
	bounce_account int(11) unsigned DEFAULT '0',

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
);

CREATE TABLE tx_newsletter_domain_model_bounceaccount (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	
	email varchar(255) DEFAULT '' NOT NULL,
	server varchar(255) DEFAULT '' NOT NULL,
	protocol varchar(255) DEFAULT '' NOT NULL,
	username varchar(255) DEFAULT '' NOT NULL,
	password varchar(255) DEFAULT '' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
);

CREATE TABLE tx_newsletter_domain_model_recipientlist (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	
	
	title varchar(255) DEFAULT '' NOT NULL,
	plain_only tinyint(1) unsigned DEFAULT '0' NOT NULL,
	lang varchar(255) DEFAULT '' NOT NULL,
	type varchar(255) DEFAULT '' NOT NULL,
	be_users varchar(255) DEFAULT '' NOT NULL,
	fe_groups varchar(255) DEFAULT '' NOT NULL,
	fe_pages varchar(255) DEFAULT '' NOT NULL,
	tt_address varchar(255) DEFAULT '' NOT NULL,
	csv_url varchar(255) DEFAULT '' NOT NULL,
	csv_separator varchar(1) DEFAULT ',' NOT NULL,
	csv_fields varchar(255) DEFAULT '' NOT NULL,
	csv_filename varchar(255) DEFAULT '' NOT NULL,
	csv_values varchar(255) DEFAULT '' NOT NULL,
	sql_statement varchar(255) DEFAULT '' NOT NULL,
	html_file varchar(255) DEFAULT '' NOT NULL,
	html_fetch_type varchar(255) DEFAULT '' NOT NULL,
	calculated_recipients varchar(255) DEFAULT '' NOT NULL,
	confirmed_recipients varchar(255) DEFAULT '' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
);

CREATE TABLE tx_newsletter_domain_model_email (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	
	
	begin_time int(11) unsigned DEFAULT '0' NOT NULL,
	end_time int(11) unsigned DEFAULT '0' NOT NULL,
	recipient_address varchar(255) DEFAULT '' NOT NULL,
	recipient_data varchar(255) DEFAULT '' NOT NULL,
	opened tinyint(1) unsigned DEFAULT '0' NOT NULL,
	bounced tinyint(1) unsigned DEFAULT '0' NOT NULL,
	host varchar(255) DEFAULT '' NOT NULL,
	newsletter int(11) unsigned DEFAULT '0',

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
);

CREATE TABLE tx_newsletter_domain_model_link (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	
	
	type varchar(255) DEFAULT '' NOT NULL,
	url varchar(255) DEFAULT '' NOT NULL,
	opened tinyint(1) unsigned DEFAULT '0' NOT NULL,
	email int(11) unsigned DEFAULT '0',

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
);