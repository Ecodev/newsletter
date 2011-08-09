<?php

class Tx_Newsletter_Persistence_BackendPidFriendly extends Tx_Extbase_Persistence_Backend {

	/**
	 * Override parent to be able to manually set the PID on the object.
	 * This will be available in TYPO3 4.6 only, but we need it also for 4.5
	 * @see http://lists.typo3.org/pipermail/typo3-project-typo3v4mvc/2011-August/010096.html
	 * @see http://git.typo3.org/TYPO3v4/CoreProjects/MVC/extbase.git?a=commit;h=6edb4c885bac63bff8accfcae5ea2ae496201ed8
	 * @param Tx_Extbase_DomainObject_DomainObjectInterface $object
	 * @return integer the PID
	 */
	protected function determineStoragePageIdForNewRecord(Tx_Extbase_DomainObject_DomainObjectInterface $object = NULL) {
		if ($object !== NULL && Tx_Extbase_Reflection_ObjectAccess::isPropertyGettable($object, 'pid')) {
			$pid = Tx_Extbase_Reflection_ObjectAccess::getProperty($object, 'pid');
			if (isset($pid)) {
				return (int) $pid;
			}
		}
		
		return parent::determineStoragePageIdForNewRecord($object);
	}
}