<?php
define ('TCDIRECTMAIL_NOT_A_BOUNCE', 1);
define ('TCDIRECTMAIL_BOUNCE_UNREMOVABLE', 2);
define ('TCDIRECTMAIL_HARDBOUNCE', 3);
define ('TCDIRECTMAIL_SOFTBOUNCE', 4);

class tx_tcdirectmail_bouncehandler {
   /* Statusvalue for the mail */
   var $status = TCDIRECTMAIL_NOT_A_BOUNCE;

   /* Receivers uid, will be populated upon process-invocation */
   var $uid;
   
   /* Newsletters page uid, will be populated upon process-invocation */
   var $pageUid;
   
   /* Newsletter receiver records uid, will be populated upon process-invocation */
   var $targetUid;

   /* Matches for soft bounces */
   var $soft = array(
      '/mailbox is full/i',
      '/quota exceeded/i',
      '/Subject:\s*Delivery unsuccessful: Mailbox has exceeded the limit/i',
      '/over quota/i',
      '/Mailbox disk quota exceeded/i',
      '/recipient was unavailable to take delivery of the message/i',
      '/Subject:\s*Undelivered Mail Returned to Sender/i',
   );
   
   /* Matches for hard bounces */
   var $hard = array(
        /* Any where in the mail */
        '/User unknown/',
        '/sorry to have to inform you that your message could not be delivered to one or more recipients./i',
        '/Delivery to the following recipients failed/i',
        '/Your message was automatically rejected by Sieve/i',
        '/sorry, no mailbox here by that name/i',
        '/550 no such/i',
        '/550 user/i',
        '/550 unknown/i',
        '/550 Invalid recipient/i',
        '/550 Host unknown/i',
        '/unknown or illegal alias/i',
        '/Unrouteable address/i',
        '/The following addresses had permanent fatal errors/i',
        '/qmail[\s\S]+this is a permanent error/i',
        '/no such user here/',
        /* On the subjectline */
        '/Subject:\s*Auto: Non existing e-mail/i',
        '/Subject:\s*Delivery Failure:/i',
        '/Subject:\s*Delivery Status Notification (Failure)/i',
        '/Subject:\s*Failed (mail|delivery|notice)/i',
        /* Both */
        '/Subject:\s*Delivery Status Notification[\s\S]+Failed/ix',
   );

   function tx_tcdirectmail_bouncehandler($mailsource) {
      /* Calculate the bounce-score */
      $this->score = 0;
      
      /* Test the soft-bounce level */
      foreach ($this->soft as $reg) {
         if (preg_match($reg, $mailsource)) {
            $this->score++;
            $this->status = TCDIRECTMAIL_SOFTBOUNCE;
         }
      }
      
      /* Test the hard-bounce level */
      foreach ($this->hard as $reg) {
         if (preg_match($reg, $mailsource)) {
            $this->score++;
            $this->status = TCDIRECTMAIL_HARDBOUNCE;
         } 
      }      
      
      /* If nothing scored, it must be a non-bounce mail. Just stop now */
      if ($this->score == 0) {
         $this->status = TCDIRECTMAIL_NOT_A_BOUNCE;
         return;
      }
      
      /* If we got this far, it is a bounce mail. Get the X-tcdirectmail-info header value to see who we we a dealing with.
         If we can get the values and the authcode checks out, return with success, else report unremovable. */
      if (preg_match('|X-tcdirectmail-info: //(.*)//|', $mailsource, $match)) {
         list($pageUid, $targetUid, $uid, $authCode, $sendid) = explode('/', $match[1]);
         $this->pageUid = intval($pageUid);
         $this->targetUid = intval($targetUid);
         $this->uid = intval($uid);
         $this->sendid = intval($sendid);
         $this->authCode = addslashes($authCode);
         
         if ($this->authCode != t3lib_div::stdAuthCode($this->uid)) {
            $this->status = TCDIRECTMAIL_BOUNCE_UNREMOVABLE;
         }           
      } else {
         $this->status = TCDIRECTMAIL_BOUNCE_UNREMOVABLE;
      }
   }
}

?>
