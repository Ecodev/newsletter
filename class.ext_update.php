<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2015
 *  All rights reserved
 *
 *  This script is part of the Typo3 project. The Typo3 project is
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
 * ************************************************************* */

/**
 * Class to migrate DB records
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ext_update
{
    /**
     * Main function, returning the HTML content of the module
     * @return	string HTML to display
     */
    public function main()
    {
        return \Ecodev\Newsletter\Update::main();
    }

    /**
     * This method checks whether it is necessary to display the UPDATE option at all
     * @return boolean		true if user have access, otherwise false
     */
    public function access()
    {
        return \Ecodev\Newsletter\Update::access();
    }
}
