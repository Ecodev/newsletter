#
# Table structure for table 'pages'
#
CREATE TABLE pages (
	tx_tcdirectmail_senttime int(11) NOT NULL,
	tx_tcdirectmail_repeat int(11) unsigned DEFAULT '0' NOT NULL,
	tx_tcdirectmail_plainconvert varchar(255) DEFAULT 'tx_tcdirectmail_plain_simple' NOT NULL,
	tx_tcdirectmail_test_target int(10) unsigned DEFAULT '0' NOT NULL,
	tx_tcdirectmail_real_target text NOT NULL,	
	tx_tcdirectmail_dotestsend tinyint(2) DEFAULT '0' NOT NULL,
	tx_tcdirectmail_attachfiles mediumtext NOT NULL,
	tx_tcdirectmail_sendername tinytext NOT NULL,
	tx_tcdirectmail_senderemail tinytext NOT NULL,
	tx_tcdirectmail_bounceaccount int(10) unsigned DEFAULT '0' NOT NULL,
	tx_tcdirectmail_spy tinyint(2) DEFAULT '0' NOT NULL,
	tx_tcdirectmail_register_clicks tinyint(2) DEFAULT '0' NOT NULL,

	KEY tx_tcdirectmail_senttime (tx_tcdirectmail_senttime),
	KEY tx_tcdirectmail_dotestsend (tx_tcdirectmail_dotestsend)
);

CREATE TABLE fe_users (
	tx_tcdirectmail_bounce int(11) DEFAULT '0' NOT NULL
);

CREATE TABLE be_users (
	tx_tcdirectmail_bounce int(11) DEFAULT '0' NOT NULL
);



#
# Table structure for table 'tx_tcdirectmail_sentlog'
#
CREATE TABLE tx_tcdirectmail_sentlog (
	uid int(11) unsigned NOT NULL auto_increment,
	pid int(11) unsigned DEFAULT 0 NOT NULL,
	begintime int(10) unsigned DEFAULT 0 NOT NULL,
	sendtime int(10) unsigned DEFAULT 0 NOT NULL,
	receiver varchar(40) NOT NULL default '',
	user_uid int(10) unsigned DEFAULT 0 NOT NULL,
	beenthere tinyint(4) unsigned DEFAULT 0 NOT NULL,
	bounced tinyint(4) unsigned DEFAULT 0 NOT NULL,
	userdata mediumtext NOT NULL,
	authcode varchar(9) NOT NULL default '',
	host varchar(15) NOT NULL default '',	
	target int(11) unsigned DEFAULT 0 NOT NULL,
    
    PRIMARY KEY (uid),
    KEY parent (pid),
    KEY normal_stat (pid,begintime),
    KEY authcode (authcode(9))
);



#
# Table structure for table 'tx_tcdirectmail_lock'
#
CREATE TABLE tx_tcdirectmail_lock (
	uid int(11) unsigned NOT NULL auto_increment,
	pid int(11) unsigned DEFAULT '0' NOT NULL,
	begintime int(11) unsigned DEFAULT '0' NOT NULL,
	stoptime int(11) unsigned DEFAULT '0' NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY beginning (begintime),
	KEY stopping (stoptime)
);


#
# Table structure for table 'tx_tcdirectmail_targets'
#
CREATE TABLE tx_tcdirectmail_targets (
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


#
# Table structure for table 'tx_tcdirectmail_clicklinks'
#

CREATE TABLE tx_tcdirectmail_clicklinks (
  sentlog int(11) unsigned NOT NULL default '0',
  linkid int(11) unsigned NOT NULL default '0',
  linktype varchar(6) NOT NULL default '',
  url varchar(255) NOT NULL default '',
  opened int(11) unsigned DEFAULT '0' NOT NULL,
  
  KEY sentlog (sentlog),
  KEY used_links_with_id (linkid,opened,linktype,sentlog),
  KEY used_links (linktype,opened,sentlog)  
);


#
# Table structure for table 'tx_tcdirectmail_bounceaccount'
#

CREATE TABLE tx_tcdirectmail_bounceaccount (
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
