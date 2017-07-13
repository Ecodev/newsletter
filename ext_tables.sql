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
	replyto_name varchar(255) DEFAULT '' NOT NULL,
	replyto_email varchar(255) DEFAULT '' NOT NULL,
	plain_converter text DEFAULT 'Ecodev\\Newsletter\\Domain\\Model\\PlainConverter\\Builtin' NOT NULL,
	attachments varchar(255) DEFAULT '' NOT NULL,
	inject_open_spy tinyint(1) unsigned DEFAULT '1' NOT NULL,
	inject_links_spy tinyint(1) unsigned DEFAULT '1' NOT NULL,
	bounce_account int(11) unsigned DEFAULT '0',

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
) ENGINE=InnoDB;

CREATE TABLE tx_newsletter_domain_model_bounceaccount (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	email varchar(255) DEFAULT '' NOT NULL,
	server varchar(255) DEFAULT '' NOT NULL,
	protocol varchar(255) DEFAULT '' NOT NULL,
	port smallint(6) unsigned DEFAULT '0' NOT NULL,
	username varchar(255) DEFAULT '' NOT NULL,
	password text NOT NULL,
	config text NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
) ENGINE=InnoDB;

CREATE TABLE tx_newsletter_domain_model_recipientlist (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	title varchar(255) DEFAULT '' NOT NULL,
	plain_only tinyint(1) unsigned DEFAULT '0' NOT NULL,
	lang int(11) unsigned DEFAULT '0' NOT NULL,
	type text DEFAULT 'Ecodev\\Newsletter\\Domain\\Model\\RecipientList\\Sql' NOT NULL,
	be_users varchar(255) DEFAULT '' NOT NULL,
	fe_groups varchar(255) DEFAULT '' NOT NULL,
	fe_pages varchar(255) DEFAULT '' NOT NULL,
	csv_url varchar(512) DEFAULT '' NOT NULL,
	csv_separator varchar(1) DEFAULT ',' NOT NULL,
	csv_fields varchar(255) DEFAULT '' NOT NULL,
	csv_filename varchar(255) DEFAULT '' NOT NULL,
	csv_values text NOT NULL,
	sql_statement text NOT NULL,
	sql_register_bounce text NOT NULL,
	sql_register_open text NOT NULL,
	sql_register_click text NOT NULL,
	html_url varchar(512) DEFAULT '' NOT NULL,
	html_fetch_type varchar(255) DEFAULT '' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
) ENGINE=InnoDB;

CREATE TABLE tx_newsletter_domain_model_email (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	begin_time int(11) unsigned DEFAULT '0' NOT NULL,
	end_time int(11) unsigned DEFAULT '0' NOT NULL,
	open_time int(11) unsigned DEFAULT '0' NOT NULL,
	bounce_time int(11) unsigned DEFAULT '0' NOT NULL,
	recipient_address varchar(255) DEFAULT '' NOT NULL,
	recipient_data text NOT NULL,
	newsletter int(11) unsigned DEFAULT '0',
	unsubscribed tinyint(1) unsigned DEFAULT '0' NOT NULL,
	auth_code varchar(32) DEFAULT '' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY newsletter (newsletter),
	KEY newsletter_end_time (newsletter,end_time),
	KEY newsletter_open_time (newsletter,open_time),
	KEY newsletter_bounce_time (newsletter,bounce_time),
	UNIQUE auth_code (auth_code),
) ENGINE=InnoDB;

CREATE TABLE tx_newsletter_domain_model_link (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	url varchar(512) DEFAULT '' NOT NULL,
	newsletter int(11) unsigned DEFAULT '0',
	opened_count int(11) unsigned NOT NULL DEFAULT '0',

	PRIMARY KEY (uid),
	KEY parent (pid)
) ENGINE=InnoDB;

CREATE TABLE tx_newsletter_domain_model_linkopened (
	uid int(11) NOT NULL auto_increment,

	link int(11) unsigned DEFAULT '0',
	email int(11) unsigned DEFAULT '0',
	is_plain tinyint(1) unsigned DEFAULT '0' NOT NULL,
	open_time int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY email_open_time (email,open_time)
) ENGINE=InnoDB;
