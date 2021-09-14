<?php
namespace CogentHealth\Healthinsurance\repository;

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
    public static function getPatientDetailById($patientId);

    /**
     * get eligibility
     *
     * @param integer $patientId
     * @return json
     */
    public static function eligibilityRequest($patientId);

}
