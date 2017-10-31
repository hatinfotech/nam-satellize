<?php

interface FTPClient_ObservableInterface
{
	/**
	 * Set an observer.
	 * @param FTPClient_ObserverInterface $observer
	 */
	public function setObserver(FTPClient_ObserverInterface $observer);
}
