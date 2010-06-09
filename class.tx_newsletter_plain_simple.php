<?php

require_once (t3lib_extMgm::extPath('newsletter').'class.tx_newsletter_plain.php');

class tx_newsletter_plain_simple extends tx_newsletter_plain {
	var $fetchMethod = 'src';

	function setHtml($html) {
		$linkcount = 0;
		$links = array();
		$html = str_replace ("\n", '', $html);
		$html = str_replace ("\r", '', $html);
		$html = preg_replace ('|.*<body[^>]*>(.*)</body>.*|Ui', '\1', $html);

		preg_match_all ('|<h([1-6])[^>]*>(.*)</h1>|Ui', $html, $match);
		foreach ($match[0] as $i => $m) {
			$fill = str_repeat('=', abs(7-$match[1][$i]));
			$html = str_replace($m, "\n\n$fill ".strtoupper($match[2][$i])." $fill\n\n", $html);
		}

		$html = preg_replace ('|<b>(.*)</b>|Ui', '*\1*', $html);
		$html = preg_replace ('|<strong>(.*)</strong>|Ui', '*\1*', $html);

		preg_match_all ('|<a[^>]*href="(.*)"[^>]*>(.*)</a>|Ui', $html, $match);
		foreach ($match[0] as $i => $m) {
			$linkcount++;
			$links[$linkcount] = $match[1][$i];
			$html = str_replace($m, $match[2][$i]."[$linkcount]", $html);
		}

		$html = str_replace ('</p>', "\n", $html);
		$html = str_replace ('<br>', "\n", $html);
		$html = str_replace ('<br />', "\n", $html);
		$html = str_replace ('</div>', "\n", $html);
		$html = str_replace ('</tr>', "\n", $html);
		$html = str_replace ('</table>', "\n", $html);
		$html = strip_tags ($html);
		$html = html_entity_decode ($html);
		$html = preg_replace("|[ ]+\n|", "\n", $html);
		$html = preg_replace("|\n{2,}|", "\n\n", $html);
		$html = implode("\n", array_map('trim', explode("\n", $html)));

		if ($linkcount > 0) {
			$html .= "\nLinks:\n";
			foreach ($links as $i => $link) {
				$html .= " $i. $link\n";
			}
		}

		$html = wordwrap($html, 72);

		$this->plainText = trim($html);
	}
}

?>
