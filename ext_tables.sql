CREATE TABLE pages (
	tx_newsletter_senttime int(11) unsigned DEFAULT '0' NOT NULL,
	tx_newsletter_repeat int(11) unsigned DEFAULT '0' NOT NULL,
	tx_newsletter_plainconvert varchar(255) DEFAULT 'tx_newsletter_plain_simple' NOT NULL,
	tx_newsletter_test_target int(10) unsigned DEFAULT '0' NOT NULL,
	tx_newsletter_real_target text NOT NULL,	
	tx_newsletter_dotestsend tinyint(2) DEFAULT '0' NOT NULL,
	tx_newsletter_attachfiles mediumtext NOT NULL,
	tx_newsletter_sendername tinytext NOT NULL,
	tx_newsletter_senderemail tinytext NOT NULL,
	tx_newsletter_bounceaccount int(10) unsigned DEFAULT '0' NOT NULL,
	tx_newsletter_spy tinyint(2) DEFAULT '0' NOT NULL,
	tx_newsletter_register_clicks tinyint(2) DEFAULT '0' NOT NULL,

	KEY tx_newsletter_senttime (tx_newsletter_senttime),
	KEY tx_newsletter_dotestsend (tx_newsletter_dotestsend)
);

CREATE TABLE fe_users (
	tx_newsletter_bounce int(11) DEFAULT '0' NOT NULL
);

CREATE TABLE be_users (
	tx_newsletter_bounce int(11) DEFAULT '0' NOT NULL
);

CREATE TABLE tx_newsletter_domain_model_emailqueue (
	uid int(11) unsigned NOT NULL auto_increment,
	pid int(11) unsigned DEFAULT '0' NOT NULL,
	begintime int(10) unsigned DEFAULT '0' NOT NULL,
	sendtime int(10) unsigned DEFAULT '0' NOT NULL,
	receiver varchar(40) NOT NULL default '',
	user_uid int(10) unsigned DEFAULT '0' NOT NULL,
	beenthere tinyint(4) unsigned DEFAULT '0' NOT NULL,
	bounced tinyint(4) unsigned DEFAULT '0' NOT NULL,
	userdata mediumtext NOT NULL,
	authcode varchar(9) NOT NULL default '',
	host varchar(15) NOT NULL default '',	
	target int(11) unsigned DEFAULT '0' NOT NULL,
    
    PRIMARY KEY (uid),
    KEY parent (pid),
    KEY normal_stat (pid,begintime),
    KEY authcode (authcode)
);

CREATE TABLE tx_newsletter_domain_model_lock (
	uid int(11) unsigned NOT NULL auto_increment,
	pid int(11) unsigned DEFAULT '0' NOT NULL,
	begintime int(11) unsigned DEFAULT '0' NOT NULL,
	stoptime int(11) unsigned DEFAULT '0' NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY beginning (begintime),
	KEY stopping (stoptime)
);

CREATE TABLE tx_newsletter_domain_model_recipientlist (
    uid int(11) unsigned NOT NULL auto_increment,
    pid int(11) unsigned DEFAULT '0' NOT NULL,
    tstamp int(11) unsigned DEFAULT '0' NOT NULL,
    crdate int(11) unsigned DEFAULT '0' NOT NULL,
    cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
    deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
    hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
    plain_only tinyint(4) unsigned DEFAULT '0' NOT NULL,
    lang int(11) DEFAULT '-1' NOT NULL,
    title tinytext NOT NULL,
    targettype tinytext NOT NULL,
    beusers mediumtext NOT NULL,
    fegroups mediumtext NOT NULL,
    fepages mediumtext NOT NULL,
    ttaddress mediumtext NOT NULL,
    csvfields mediumtext NOT NULL,
    csvfilename tinytext NOT NULL,
    csvvalues mediumtext NOT NULL,
    csvurl tinytext NOT NULL,
    csvseparator char(3) NOT NULL default ',',
    rawsql mediumtext NOT NULL,
    htmlfile tinytext NOT NULL,
    htmlfetchtype tinytext NOT NULL,
    confirmed_receivers tinyint(1) DEFAULT '0' NOT NULL,
    calculated_receivers tinyint(1) DEFAULT '0' NOT NULL,
    
    PRIMARY KEY (uid),
    KEY parent (pid)
);

CREATE TABLE tx_newsletter_domain_model_clicklink (
  sentlog int(11) unsigned NOT NULL default '0',
  linkid int(11) unsigned NOT NULL default '0',
  linktype varchar(6) NOT NULL default '',
  url varchar(255) NOT NULL default '',
  opened int(11) unsigned DEFAULT '0' NOT NULL,
  
  KEY sentlog (sentlog),
  KEY used_links_with_id (linkid,opened,linktype,sentlog),
  KEY used_links (linktype,opened,sentlog)  
);

CREATE TABLE tx_newsletter_domain_model_bounceaccount (
    uid int(11) unsigned NOT NULL auto_increment,
    pid int(11) unsigned DEFAULT '0' NOT NULL,
    tstamp int(11) unsigned DEFAULT '0' NOT NULL,
    crdate int(11) unsigned DEFAULT '0' NOT NULL,
    cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
    deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
    hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
    email tinytext NOT NULL,
    servertype varchar(6) DEFAULT 'pop3' NOT NULL,
    server tinytext NOT NULL,
    username tinytext NOT NULL,
    passwd tinytext NOT NULL,
    
    PRIMARY KEY (uid),
    KEY parent (pid) 
);
