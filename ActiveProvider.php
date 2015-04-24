<?php
/**
 * Piwik - Open source web analytics
 * 
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html Gpl v3 or later
 * @version $Id: ActiveProvider.php 94 2011-04-05 13:05:55Z apang $
 * 
 * @category Piwik_Plugins
 * @package Piwik_ActiveProvider
 */

/**
 * ActiveProvider enhances the Provider report using the Public Suffix List
 * to generate more accurate provider names in second/third level domains
 *
 * @package Piwik_ActiveProvider
 */
class Piwik_ActiveProvider extends Piwik_Plugin
{
	public function getInformation()
	{
		return array(
			'name' => 'Active Provider',
			'description' => 'Use the Public Suffix List to enhance the accuracy of the Provider report. The Public Suffix List (at http://publicsuffix.org) is a cross-vendor initiative to provide an accurate list of domain name suffixes.',
			'author' => 'VIP Software Technologies Inc.',
			'author_homepage' => 'http://ActiveAnalytics.org/',
			'version' => '1.2.1',
			'TrackerPlugin' => true,
		);
	}

	public function getListHooksRegistered()
	{
		return array(
			'Provider.getCleanHostname' => 'getCleanHostname',
		);
	}

	public function getCleanHostname($notification)
	{
		$cleanHostname =& $notification->getNotificationObject();
		$hostname = $notification->getNotificationInfo();

		try {
			/**
			 * @see http://www.dkim-reputation.org/regdom-libs/
			 */
			require_once dirname(__FILE__) . '/registered-domain-libs/PHP/effectiveTLDs.inc.php';
			require_once dirname(__FILE__) . '/registered-domain-libs/PHP/regDomain.inc.php';

			// returns null if $hostname is a TLD
			$cleanHostname = getRegisteredDomain($hostname);

			if($cleanHostname === null)
				$cleanHostname = $hostname;
		} catch(Exception $e) {
		}
	}
}
