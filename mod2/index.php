<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2006 -2008 Daniel Schledermann (daniel@schledermann.net)
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
/**
 * Module 'Newsletter' for the 'newsletter' extension.
 *
 * @author   Daniel Schledermann <daniel@schledermann.net>
 */


// DEFAULT initialization of a module [BEGIN]
$LANG->includeLLFile("EXT:newsletter/mod2/locallang.xml");
require_once (PATH_t3lib."class.t3lib_scbase.php");
$BE_USER->modAccess($MCONF,1);   // This checks permissions and exits if the users has no permission for entry.
// DEFAULT initialization of a module [END]
 
$ICON_PATH = $BACK_PATH.'gfx/';
require_once (t3lib_extMgm::extPath('newsletter').'class.tx_newsletter_tools.php');
require_once (t3lib_extMgm::extPath('newsletter').'class.tx_newsletter_mailer.php');

require_once (t3lib_extMgm::extPath('newsletter').'/Classes/Domain/Model/Newsletter.php');
require_once (t3lib_extMgm::extPath('newsletter').'/Classes/Domain/Repository/NewsletterRepository.php');

class tx_newsletter_module1 extends t3lib_SCbase {

	var $pageinfo;
	
	/**
	 * @var Tx_Newsletter_Domain_Repository_NewsletterRepository
	 */
	private $newsletterRepository = null;
	
	/**
	 * @var Tx_Newsletter_Domain_Model_Newsletter
	 */
	private $newsletter = null;

	/**
	 *
	 */
	function init()
	{
		parent::init();

		$this->newsletterRepository = t3lib_div::makeInstance('Tx_Newsletter_Domain_Repository_NewsletterRepository');
		$this->newsletter = $this->newsletterRepository->getLatest($_REQUEST['id']);
	}

	/**
	 * Adds items to the ->MOD_MENU array. Used for the function menu selector.
	 */
	function menuConfig()   {
		global $LANG, $MODULE_PARTS;

		$this->MOD_MENU = Array (
         "function" => Array (
            "status" => $LANG->getLL("status"),
            'preview' => $LANG->getLL('preview'),
		)
		);

		parent::menuConfig();

	}

	// If you chose "web" as main module, you will need to consider the $this->id parameter which will contain the uid-number of the page clicked in the page tree
	/**
	 * Main function of the module. Write the content to $this->content
	 */
	function main()   {
		global $BE_USER,$LANG,$BACK_PATH,$TCA_DESCR,$TCA,$CLIENT,$TYPO3_CONF_VARS;

		// Access check!
		// The page will show only if there is a valid page and if this page may be viewed by the user
		$this->pageinfo = t3lib_BEfunc::readPageAccess($this->id,$this->perms_clause);
		$access = is_array($this->pageinfo) ? 1 : 0;

		if (($this->id && $access) || ($BE_USER->user["admin"] && !$this->id))   {
			 
			// Draw the header.
			$this->doc = t3lib_div::makeInstance("bigDoc");
			$this->doc->backPath = $BACK_PATH;
			$this->doc->form='<form name="newsletterform" action="" method="POST">';

			// JavaScript
			$this->doc->JScode = '
            <script language="javascript" type="text/javascript">
               script_ended = 0;
               function jumpToUrl(URL)   {
                  document.location = URL;
               }
			   function checkAll(elementName){
					var boolValue = elementName.checked;
     				for (var i=0;i<document.newsletterform.elements.length;i++){
				        var e = document.newsletterform.elements[i];
       					if (e.name != elementName.name)
         				e.checked = boolValue;
     				}
   				}
            </script>
         ';
			$this->doc->postCode='
            <script language="javascript" type="text/javascript">
               script_ended = 1;
               if (top.fsMod) top.fsMod.recentIds["web"] = '.intval($this->id).';
            </script>
         ';

			$headerSection = $this->doc->getHeader("pages",$this->pageinfo,$this->pageinfo["_thePath"])."<br>".$LANG->sL("LLL:EXT:lang/locallang_core.php:labels.path").": ".t3lib_div::fixed_lgd_pre($this->pageinfo["_thePath"],50);

			// Filter out functions defined as disallowed in the user-ts.
			if (is_array($GLOBALS['BE_USER']->userTS['newsletter.']['modfuncDisallow.'])) {
				foreach ($GLOBALS['BE_USER']->userTS['newsletter.']['modfuncDisallow.'] as $func => $disallowed) {
					if ($disallowed) {
						if ($func == $this->MOD_SETTINGS['function']) {
							die ("Access denied");
						}
						unset($this->MOD_MENU['function'][$func]);
					}
				}
			}
			 
			$this->content.=$this->doc->startPage($LANG->getLL("title"));
			$this->content.=$this->doc->header($LANG->getLL("title"));
			$this->content.=$this->doc->spacer(5);
			$this->content.=$this->doc->section("",$this->doc->funcMenu($headerSection,t3lib_BEfunc::getFuncMenu($this->id,"SET[function]",$this->MOD_SETTINGS["function"],$this->MOD_MENU["function"])));
			$this->content.=$this->doc->divider(5);

			// Render content:
			$this->moduleContent();

			 
			// ShortCut
			if ($BE_USER->mayMakeShortcut())   {
				$this->content.=$this->doc->spacer(20).$this->doc->section("",$this->doc->makeShortcutIcon("id",implode(",",array_keys($this->MOD_MENU)),$this->MCONF["name"]));
			}

			$this->content.=$this->doc->spacer(10);
		} else {
			// If no access or if ID == zero

			$this->doc = t3lib_div::makeInstance("mediumDoc");
			$this->doc->backPath = $BACK_PATH;

			$this->content.=$this->doc->startPage($LANG->getLL("title"));
			$this->content.=$this->doc->header($LANG->getLL("title"));
			$this->content.=$this->doc->spacer(5);
			$this->content.=$this->doc->spacer(10);
		}
	}

	/**
	 * Prints out the module HTML
	 */
	function printContent()   {

		$this->content.=$this->doc->endPage();
		echo $this->content;
	}
	 
	/**
	 * Generates the module content
	 */
	function moduleContent()   {
		global $LANG, $TYPO3_DB;

		/* Must have an id */
		if (!$_REQUEST['id']) return;

		switch((string)$this->MOD_SETTINGS["function"])   {
			case 'preview':
				$content = $this->viewPreview();
				$this->content.=$this->doc->section($LANG->getLL("preview"),$content,0,1);
				break;

			case 'status':
			default :
				$content = $this->viewStatus();
				$this->content.=$this->doc->section($LANG->getLL("status"),$content,0,1);
				break;
		}
	}
	 
	/* View all sorts of stuff about the current page. */
	function viewStatus() {
		global $TYPO3_DB;
		global $LANG;
		global $ICON_PATH;
		global $BE_USER;


		/* Schedule a send? */
		if ($_REQUEST['send_now']) {
			$this->newsletter->setPlannedTime(time());
			$this->newsletterRepository->update($this->newsletter);
		}

		/* Invoke the mailer? */
		if ($_REQUEST['invoke_mailer']) {
			$this->invokeMailer();
		}

		/* Check if there is a domain-name set */
		if($BE_USER->user['admin']){
			$output .= '<h3>'.$LANG->getLL('domain_name').'</h3>';

			$domain = $this->newsletter->getDomain();
			if ($domain) {
				$output .= '<p><img src="'.$ICON_PATH.'icon_ok.gif" />'.str_replace ('###DOMAIN###', $domain, $LANG->getLL('domain_ok')).'</p>';
			} else {
				$output .= '<p><img src="'.$ICON_PATH.'icon_fatalerror.gif" />'.$LANG->getLL('domain_notok').'</p>';
			}

			$output .= '<br />';
		}

		/* Write the sender name */
		$output .= '<h3>'.$LANG->getLL('sender_name').'</h3>';

		$sender = $this->newsletter->getSenderName();
		$output .= '<p>'.str_replace ('###SENDER###', $sender, $LANG->getLL('sender_for_page')).'</p>';
		$output .= '<br />';

		/* Write the sender email */
		$output .= '<h3>'.$LANG->getLL('sender_email').'</h3>';

		$email = $this->newsletter->getSenderEmail();
		$output .= '<p>'.str_replace ('###EMAIL###', $email, $LANG->getLL('email_for_page')).'</p>';
		$output .= '<br />';


		/* Get starttime for lock-records */
		$newsletterBegan = $this->newsletter->getBeginTime();
		/* Get current time.status */
		$plannedTime = $this->newsletter->getPlannedTime();

		$output .= '<form>';


		/* Real sends? */
		$output .= '<h3>'.$LANG->getLL('status_real_receivers').'</h3>';
		$recipientList = $this->newsletter->getRecipientListConcreteInstance();
		if ($recipientList) {
				
			$total_to_send = $recipientList->getCount();
				
			$output .= $recipientList->getExtract();
				
			if ($plannedTime > 0) {
				$output .= '<p>'.str_replace('###TIME_TO_SEND###', $plannedTime->format(DateTime::ISO8601),
				str_replace('###NUMBERS_TO_SEND###', $total_to_send, $LANG->getLL('scheduled_info'))) .'</p>';
			} else {
				$output .= '<p><strong>'.$LANG->getLL('not_scheduled').'</strong></p>';
				$output .= '<br />';
				$output .= '<p>'.$LANG->getLL('total_receivers').' : <strong>'.$total_to_send.'</strong></p>';
				$output .= '<br />';
				$output .= '<p><input onclick="return confirm(\''.$LANG->getLL('confirm_text').'\');" style="cursor:pointer;" type="submit" name="send_now" value="'.$LANG->getLL('send_now').'" /></p>';
				$output .= '</p>';
			}


			if ($newsletterBegan) {
				$rs = $TYPO3_DB->exec_SELECTquery('COUNT(*)', 'tx_newsletter_domain_model_email', 'end_time > 0 AND newsletter = ' . $this->newsletter->getUid());
				list ($already_sent) = $TYPO3_DB->sql_fetch_row($rs);

				$output .= '<p>Actually started to send on <strong>'. $newsletterBegan->format(DateTime::ISO8601) .'</strong></p>';
				$output .= "<p>Emails sent: <strong>$already_sent / $total_to_send</strong></p>";
			}
				
			// If all emails were not send, we may offer a to invoke the mailer
			if ($already_sent < $total_to_send && tx_newsletter_tools::confParam('show_invoke_mailer') && $BE_USER->user['admin']) {
				$output .= '<br />';
				$output .= '<input style="cursor:pointer;" type="submit" name="invoke_mailer" value="Invoke mailer engine" />';
			}
				
		} else {
			$output .= '<p><strong>'.$LANG->getLL('no_real_receivers').'</strong></p>';
		}
		$output .= '<br />';

		$output .= '</form>';

		return $output;
	}

	function invokeMailer() {

		// Fill the spool
		tx_newsletter_tools::createSpool($this->newsletter);

		// Go on and run the queue
		tx_newsletter_tools::runSpoolOne($this->newsletter);
	}

	/* View number of mails delivered in the past */
	function viewPreview() {
		global $TYPO3_DB;
		global $LANG;
		global $BACK_PATH;
	  
		/* Get list of receivers */
		$rs = $TYPO3_DB->exec_SELECTquery('*', 'pages', "uid = $_REQUEST[id]");
		$page = $TYPO3_DB->sql_fetch_assoc($rs);
	  
		$mailer = tx_newsletter_tools::getConfiguredMailer($this->newsletter);
	  	
		if (!$this->newsletter->getRecipientList())
			return "ERROR: not recipient list defined";
		
		$out .= '<p>' . $this->editTarget($this->newsletter->getRecipientList()) . '</p>';
		$out .= '<table>';
		$out .= '<tr>';
		$out .= '<td><b>'.$LANG->getLL('receiver').'</b></td>';
		$out .= '<td><b>'.$LANG->getLL('status').'</b></td>';
		$out .= '<td><b>'.$LANG->getLL('num_fields').'</b></td>';
		$out .= '<td><b>'.$LANG->getLL('missing_fields').'</b></td>';
		$out .= '<td><b>'.$LANG->getLL('preview').'</b></td>';
		$out .= '</tr>';

		$recipientList = $this->newsletter->getRecipientListConcreteInstance();

		while ($record = $recipientList->getRecipient())
		{
			// Build a fake email
			$email = new Tx_Newsletter_Domain_Model_Email();
			$email->setRecipientAddress($record['email']);
			$email->setRecipientData($record);
			
			$out .= '<tr>';
			$out .= "<td><a href=\"mailto:$record[email]\">$record[email]</td>";


			/* Number of fields */
			$num_fields = count($record);

			// Number of unsubstituted fields
			$mailer->prepare($email);
			preg_match_all('|###[a-z0-9_]+###|i', $mailer->getHtml(), $nonfields_html);
			preg_match_all('|###[a-z0-9_]+###|i', $mailer->getPlain(), $nonfields_plain);
			$num_nonfields = max(count($nonfields_html[0]), count($nonfields_plain[0]));

			/* Ok fields icon? */
			$status_url = $GLOBALS['BACK_PATH'].'gfx';
			if ($num_nonfields != 0) {
				$out .= '<td><img src="'.$GLOBALS['BACK_PATH'].'gfx/icon_fatalerror.gif" /></td>';
			} else {
				$out .= '<td><img src="'.$GLOBALS['BACK_PATH'].'gfx/icon_ok.gif" /></td>';
			}

			$out .= "<td>$num_fields</td>";
			$out .= "<td>$num_nonfields</td>";
			$out .= '<td>';

			if (!$record['plain_only']) {
				$out .= $this->previewLink($this->newsletter, 'html', $record['email']);
			} else {
				$out .= $GLOBALS['LANG']->getLL('preview_html');
			}

			$out .= '&nbsp;'.$this->previewLink($this->newsletter, 'plain', $record['email']).'</td>';
			$out .= "</tr>\n";

		}
	  
		$out .= '</table>';
	  
		return $out;
	}

	function previewLink(Tx_Newsletter_Domain_Model_Newsletter $newsletter, $type, $email) {
		return '<a target="_new" href="'.$GLOBALS['BACK_PATH']
		.t3lib_extMgm::extRelPath('newsletter')
		.'web/view.php?newsletter='. $newsletter->getUid() .'&email='.rawurlencode($email).'&type='.$type.'">'
		.$GLOBALS['LANG']->getLL("preview_$type")
		.'</a>';
	}

	function editTarget(Tx_Newsletter_Domain_Model_RecipientList $recipientList) {
		global $BACK_PATH;

		$out .= '<a href="'.$BACK_PATH.'alt_doc.php?returnUrl='.rawurlencode(t3lib_div::getIndpEnv("REQUEST_URI"));
		$out .= '&edit[tx_newsletter_domain_model_recipientlist][' . $recipientList->getUid() . ']=edit">';
		$out .= '<img src="'.$BACK_PATH.t3lib_extMgm::extRelPath('newsletter').'/Resources/Public/Icons/tx_newsletter_domain_model_recipientlist.gif" /> ';
		$out .= $recipientList->getTitle() . ' (' . $recipientList->getUid() . ')';
	  
		return $out;
	}
}



if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/newsletter/mod2/index.php"])   {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/newsletter/mod2/index.php"]);
}




// Make instance:
$SOBE = t3lib_div::makeInstance("tx_newsletter_module1");
$SOBE->init();

// Include files?
foreach($SOBE->include_once as $INC_FILE)   include_once($INC_FILE);

$SOBE->main();
$SOBE->printContent();

