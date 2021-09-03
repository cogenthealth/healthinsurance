<?php

interface HiInterface
{
    /**
     * class initialization.
     *
     * @param integer $patientId
     * @return json
     */
    public static function init();

    /**
     * Get patient details from identifier.
     *
     * @param integer $patientId
     * @return json
     */
    public static function getPatientDetailById(int $patientId);

    /**
     * get eligibility
     *
     * @param integer $patientId
     * @return json
     */
    public static function eligibilityRequest(int $patientId);

}
