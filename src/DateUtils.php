<?php

namespace Glamus\Utils;

/**
 * Various functions concerning date.
 *
 * Function summary:
 *  - getLogTimeStamp
 *  - getDateAsStringYearMonthDay
 *
 * 1.0.0        2021-10-18  michiels
 *              - Erstimplementierung
 *
 * @copyright GLAMUS GmbH
 * @author Patrick Michiels <michiels@glamus.de>
 */
class DateUtils {


  /**
   * DateUtils constructor.
   */
  public function __construct() {
  }


    /**
     * Returns the current date as string in Year_month_day format.
     *
     * @return string
     *   The current date as string in Year_month_day format.
     */
    public function getDateAsStringYearMonthDay(): string {
        return date('Y_m_d');
    }

    /**
     * Returns the current date in "Year-month-day Hour:minute:second" format.
     *
     * @return string
     *   The current date as string in Year_month_day format.
     */
    public function getLogTimeStamp(): string {
        return date("Y-m-d H:i:s");
    }

}
