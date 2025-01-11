<?php

declare(strict_types=1);

namespace App\core\irtf;

/**
 * /home/webdev2024/classes/core/irtf/IrtfLinks.php
 *
 * This class provides static methods to generate commonly used URLs
 * throughout the IRTF website. It replaces the old irtflinks.inc approach,
 * offering a cleaner, organized, and PSR-12 compliant solution.
 *
 * @category Utilities
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class IrtfLinks
{
    // Root

    /**
     * Returns the URL for the homepage.
     *
     * @return string The URL for the homepage.
     */
    public static function getHome(): string
    {
        return '/';
    }

    /**
     * Returns the URL for the site map page.
     *
     * @return string The URL for the site map page.
     */
    public static function getSiteMap(): string
    {
        return '/siteMap.php';
    }

    /**
     * Returns the URL for the help page.
     *
     * @return string The URL for the help page.
     */
    public static function getHelp(): string
    {
        return '/help.php';
    }

    // Information: General

    /**
     * Returns the URL for the "About Us" page.
     *
     * @return string The URL for the "About Us" page.
     */
    public static function getAbout(): string
    {
        return '/information/about.php';
    }

    /**
     * Returns the URL for the "Contacts" page.
     *
     * @return string The URL for the "Contacts" page.
     */
    public static function getContacts(): string
    {
        return '/information/contacts.php';
    }

    /**
     * Returns the URL for the "Credits" page.
     *
     * @return string The URL for the "Credits" page.
     */
    public static function getCredits(): string
    {
        return '/information/credits.php';
    }

    /**
     * Returns the URL for the metrics document.
     *
     * @return string The URL for the metrics document (PDF).
     */
    public static function getMetrics(): string
    {
        return '/information/metrics/IRTF_metrics_200630.pdf';
    }

    /**
     * Returns the URL for the "Past Announcements" page.
     *
     * @return string The URL for the "Past Announcements" page.
     */
    public static function getPastAnnouncements(): string
    {
        return '/information/pastAnnouncements.php';
    }

    /**
     * Returns the URL for the "Video" page.
     *
     * @return string The URL for the "Video" page.
     */
    public static function getVideo(): string
    {
        return '/information/video.php';
    }

    /**
     * Returns the URL for the "Remote Locations" page.
     *
     * @return string The URL for the "Remote Locations" page.
     */
    public static function getRemoteLocations(): string
    {
        return '/information/remlocations.php';
    }

    /**
     * Returns the URL for the "Contact Us" page.
     *
     * @return string The URL for the "Contact Us" page.
     */
    public static function getContactUs(): string
    {
        return '/information/contactus.php';
    }

    /**
     * Returns the URL for the "Past Meetings" page.
     *
     * @return string The URL for the "Past Meetings" page.
     */
    public static function getPastMeetings(): string
    {
        return '/information/pastMeetings.php';
    }

    // Information: Newsletter

    /**
     * Returns the URL for the "News" page.
     *
     * @return string The URL for the "News" page.
     */
    public static function getNews(): string
    {
        return '/information/newsletter/index.php';
    }

    /**
     * Returns the URL for the "News" page with a specific semester.
     *
     * @param string $semester The semester parameter for the news page.
     * @return string The URL for the "News" page for a specific semester.
     */
    public static function getNewsBySemester(string $semester): string
    {
        return "/information/newsletter/index.php?s={$semester}";
    }

    /**
     * Returns the URL for the "News Index" page.
     *
     * @return string The URL for the "News Index" page.
     */
    public static function getNewsIndex(): string
    {
        return '/information/newsletter/index.php?s=index';
    }

    // Information: Miscellaneous

    /**
     * Returns the URL for the "Astroday" presentation.
     *
     * @return string The URL for the "Astroday" presentation (PPT).
     */
    public static function getAstroday(): string
    {
        return '/information/miscellaneous/Astroday2k2.ppt';
    }

    // Documents

    /**
     * Returns the URL for the planning documents page.
     *
     * @return string
     */
    public static function getPlanning(): string
    {
        return '/Documents/index.php';
    }

    // Meetings

    /**
     * Returns the URL for the general meetings page.
     *
     * @return string
     */
    public static function getMeetings(): string
    {
        return '/meetings';
    }

    /**
     * Returns the URL for the 2018 future meetings page.
     *
     * @return string
     */
    public static function getFuture2018Meeting(): string
    {
        return '/meetings/irtf_future_2018';
    }

    /**
     * Returns the URL for the 2018 future meetings presentations page.
     *
     * @return string
     */
    public static function getFuture2018Presentations(): string
    {
        return '/meetings/irtf_future_2018/Presentations';
    }

    /**
     * Returns the URL for the 2018 future meetings white papers page.
     *
     * @return string
     */
    public static function getFuture2018WhitePapers(): string
    {
        return '/meetings/irtf_future_2018/WhitePapers';
    }

    /**
     * Returns the URL for the Astrophysics Decadal (January 26, 2019) PDF.
     *
     * @return string
     */
    public static function getAstrophysicsDecadal190126(): string
    {
        return '/meetings/irtf_future_2018/WhitePapers/IRTF_Astrophysics_Decadal_190126.pdf';
    }

    /**
     * Returns the URL for the Planetary Decadal (July 10, 2020) PDF.
     *
     * @return string
     */
    public static function getPlanetaryDecadal200710(): string
    {
        return '/meetings/irtf_future_2018/WhitePapers/IRTF_Planetary_Decadal_200710.pdf';
    }

    // Observing

    /**
     * Returns the URL for the observing section's top-level page.
     *
     * @return string
     */
    public static function getObservingTop(): string
    {
        return '/observing';
    }

    /**
     * Returns the URL for the observing feedback form.
     *
     * @return string
     */
    public static function getFeedback(): string
    {
        return '/observing/feedback/feedback.php';
    }

    /**
     * Returns the URL for the observing preparation checklist page.
     *
     * @return string
     */
    public static function getChecklist(): string
    {
        return '/observing/preparingForRun.php';
    }

    /**
     * Returns the URL for the observing credit card form in DOC format.
     *
     * @return string
     */
    public static function getCreditDoc(): string
    {
        return '/observing/creditcardform.doc';
    }

    /**
     * Returns the URL for the observing credit card form in PDF format.
     *
     * @return string
     */
    public static function getCreditPdf(): string
    {
        return '/observing/creditcardform.pdf';
    }

    /**
     * Returns the URL for the driver clearance form in PDF format.
     *
     * @return string
     */
    public static function getDriverClearance(): string
    {
        return '/observing/driverclearance.pdf';
    }

    /**
     * Returns the URL for the observing index page.
     *
     * @return string
     */
    public static function getObserving(): string
    {
        return '/observing/index.php';
    }

    /**
     * Returns the URL for the observing information page.
     *
     * @return string
     */
    public static function getObservingInfo(): string
    {
        return '/observing/information.php';
    }

    /**
     * Returns the URL for the liquid helium information page.
     *
     * @return string
     */
    public static function getHelium(): string
    {
        return '/observing/liquidHelium.php';
    }

    /**
     * Returns the URL for the observer information page.
     *
     * @return string
     */
    public static function getObserverInfo(): string
    {
        return '/observing/observerInfo.php';
    }

    /**
     * Returns the URL for the observer manual page.
     *
     * @return string
     */
    public static function getObserverManual(): string
    {
        return '/observing/observerManual.php';
    }

    /**
     * Returns the URL for the ORF (Observer Requirements Form) page.
     *
     * @return string
     */
    public static function getORF(): string
    {
        return '/observing/orf';
    }

    /**
     * Returns the URL for the Hale Pohaku services PDF.
     *
     * @return string
     */
    public static function getHalePohakuServices(): string
    {
        return '/observing/servicesHalePohaku.pdf';
    }

    /**
     * Returns the URL for the observing schedule page.
     *
     * @return string
     */
    public static function getObservingSchedule(): string
    {
        return '/observing/schedule.php';
    }

    /**
     * Returns the URL for the storage and shipping page.
     *
     * @return string
     */
    public static function getStorage(): string
    {
        return '/observing/storageShipping.php';
    }

    /**
     * Returns the URL for the storage form PDF.
     *
     * @return string
     */
    public static function getStorageForm(): string
    {
        return '/observing/storage_print.pdf';
    }

    /**
     * Returns the URL for the telescope specifications page.
     *
     * @return string
     */
    public static function getTelescopeSpecs(): string
    {
        return '/observing/telescopeSpecs.php';
    }

    /**
     * Returns the URL for the IDL (Interactive Data Language) information page.
     *
     * @return string
     */
    public static function getIDL(): string
    {
        return '/observing/computer/idl.php';
    }

    // Observing: Maps

    /**
     * Returns the URL for the Maps Index page.
     *
     * @return string
     */
    public static function getMapIndex(): string
    {
        return '/observing/maps/index.php';
    }

    /**
     * Returns the URL for the Keybox Map page.
     *
     * @return string
     */
    public static function getMapKeybox(): string
    {
        return '/observing/maps/keybox.php';
    }

    /**
     * Returns the URL for the Access Road Map PDF.
     *
     * @return string
     */
    public static function getMapAccessRd(): string
    {
        return '/observing/maps/access-road.pdf';
    }

    /**
     * Returns the URL for the Big Island Map (external link).
     *
     * @return string
     */
    public static function getMapBigIsland(): string
    {
        return 'http://www.ifa.hawaii.edu/maps/big_isle_map2.shtml';
    }

    /**
     * Returns the URL for the Hilo Map (external link).
     *
     * @return string
     */
    public static function getMapHiloMap(): string
    {
        return 'http://www.ifa.hawaii.edu/maps/hilo_map.shtml';
    }

    /**
     * Returns the URL for the Hale Pohaku Map (external link).
     *
     * @return string
     */
    public static function getMapHPMap(): string
    {
        return 'http://www.ifa.hawaii.edu/maps/hp_map.shtml';
    }

    /**
     * Returns the URL for the Hilo Office Map PDF.
     *
     * @return string
     */
    public static function getMapHiloOffice(): string
    {
        return '/observing/maps/hilooffice.pdf';
    }

    /**
     * Returns the URL for the IFA Manoa Map PDF.
     *
     * @return string
     */
    public static function getMapIfaManoa(): string
    {
        return '/observing/maps/ifa-hnl.pdf';
    }

    /**
     * Returns the URL for the Mauna Kea Summit Map (external link).
     *
     * @return string
     */
    public static function getMapMaunaKea(): string
    {
        return 'http://www.ifa.hawaii.edu/maps/summit_map.shtml';
    }

    /**
     * Returns the URL for the Hawaii State Maps (external link).
     *
     * @return string
     */
    public static function getMapState(): string
    {
        return 'https://www.ifa.hawaii.edu/maps/hawaii_maps.shtml';
    }

    /**
     * Returns the URL for the Oahu Maps (external link).
     *
     * @return string
     */
    public static function getMapOahu(): string
    {
        return 'http://www.ifa.hawaii.edu/maps/oahu_maps.shtml';
    }

    // Observing: Sidebar Links

    /**
     * Returns the URL for the Applying for Time page.
     *
     * @return string
     */
    public static function getApplyingForTime(): string
    {
        return '/observing/applyingForTime.php';
    }

    /**
     * Returns the URL for the Preparing for the Run page.
     *
     * @return string
     */
    public static function getPreparingForRun(): string
    {
        return '/observing/preparingForRun.php';
    }

    /**
     * Returns the URL for the During the Run page.
     *
     * @return string
     */
    public static function getDuringTheRun(): string
    {
        return '/observing/duringTheRun.php';
    }

    /**
     * Returns the URL for the Post Run page.
     *
     * @return string
     */
    public static function getPostRun(): string
    {
        return '/observing/postRun.php';
    }

    // Observing: Application Forms

    /**
     * Returns the URL for the main application forms page.
     *
     * @return string
     */
    public static function getApplication(): string
    {
        return '/observing/applicationForms.php';
    }

    /**
     * Returns the URL for the application form.
     *
     * @return string
     */
    public static function getApplicationForm(): string
    {
        return '/observing/application/application.php';
    }

    /**
     * Returns the URL for the application FAQ page.
     *
     * @return string
     */
    public static function getApplicationFAQ(): string
    {
        return '/observing/application/applicationFAQ.php';
    }

    /**
     * Returns the URL for the Dual Anonymous Peer Review (DAPR) info page.
     *
     * @return string
     */
    public static function getApplicationDAPR(): string
    {
        return '/observing/applicationDAPRInfo.php';
    }

    /**
     * Returns the URL for NASA's Dual Anonymous Peer Review page.
     *
     * @return string
     */
    public static function getNasaApplicationDAPR(): string
    {
        return 'https://science.nasa.gov/researchers/dual-anonymous-peer-review';
    }

    /**
     * Returns the URL for the IRTF-SOFIA Joint Proposals PDF.
     *
     * @return string
     */
    public static function getSofia(): string
    {
        return '/observing/IRTF_SOFIA_Jointproposals_Feb2022.pdf';
    }

    /**
     * Returns the URL for the observing application forms directory.
     *
     * @return string
     */
    public static function getObservingApplications(): string
    {
        return '/observing/applicationForms';
    }

    /**
     * Returns the URL for the application form staff page (self-referential).
     *
     * @return string
     */
    public static function getStaffApplication(): string
    {
        return "{$_SERVER['PHP_SELF']}?staff";
    }

    /**
     * Returns the URL for the test application form page.
     *
     * @return string
     */
    public static function getTestApplication(): string
    {
        return '/observing/application/application_TEST.php';
    }

    // Observing: Applications

    /**
     * Returns the URL for the current proposal attachment document (vSept2023).
     *
     * @return string
     */
    public static function getObservingApplicationsDoc(): string
    {
        return '/observing/application/ProposalAttachment_vSept2023.docx';
    }

    /**
     * Returns the URL for the EPSF style file for applications.
     *
     * @return string
     */
    public static function getObservingApplicationsSty(): string
    {
        return '/observing/application/epsf.sty';
    }

    /**
     * Returns the URL for the current LaTeX proposal attachment (vSept2023).
     *
     * @return string
     */
    public static function getObservingApplicationsTex(): string
    {
        return '/observing/application/ProposalAttachment_vSept2023.tex';
    }

    // Observing: Calls For Proposals

    /**
     * Returns the URL for the proposals page.
     *
     * @return string
     */
    public static function getCallsForProposals(): string
    {
        return '/observing/callforproposals/index.php';
    }

    /**
     * Returns the URL for the proposals index.
     *
     * @return string
     */
    public static function getCallsForProposalsIndex(): string
    {
        return '/observing/callforproposals/index.php?s=index';
    }

    /**
     * Returns the URL for a specific semester's proposals.
     *
     * @param string $sem The semester code (e.g., "2023A").
     * @return string
     */
    public static function getCallsForProposalsSemester(string $sem): string
    {
        return "/observing/callforproposals/index.php?s={$sem}";
    }

    // Observing: Computers

    /**
     * Returns the URL for the remote observing page.
     *
     * @return string
     */
    public static function getRemoteObserving(): string
    {
        return '/observing/computer';
    }

    /**
     * Returns the URL for the observer computing section.
     *
     * @return string
     */
    public static function getObserverComputing(): string
    {
        return self::getRemoteObserving();
    }

    /**
     * Returns the URL for observer data backup page.
     *
     * @return string
     */
    public static function getObserverDataBackup(): string
    {
        return '/observing/computer/data_backup.php';
    }

    /**
     * Returns the URL for the observer data release policy.
     *
     * @return string
     */
    public static function getObserverDataReleasePolicy(): string
    {
        return '/observing/computer/data_release_policy.php';
    }

    /**
     * Returns the URL for the observer guest accounts page.
     *
     * @return string
     */
    public static function getObserverAccounts(): string
    {
        return '/observing/computer/guests.php';
    }

    /**
     * Returns the URL for the VNC page in the observer computing section.
     *
     * @return string
     */
    public static function getObserverVnc(): string
    {
        return '/observing/computer/vnc.php';
    }

    /**
     * Returns the URL for the RealVNC homepage.
     *
     * @return string
     */
    public static function getRealVnc(): string
    {
        return 'http://www.realvnc.com';
    }

    /**
     * Returns the URL for the RealVNC download page.
     *
     * @return string
     */
    public static function getRealVncDownload(): string
    {
        return 'http://www.realvnc.com/download/viewer';
    }

    /**
     * Returns the URL for the observer Zoom page.
     *
     * @return string
     */
    public static function getObserverZoom(): string
    {
        return '/observing/computer/zoom.php';
    }

    /**
     * Returns the URL for the Zoom meeting instructions PDF.
     *
     * @return string
     */
    public static function getZoomMeetingInstructions(): string
    {
        return '/observing/computer/ZoomMeetingInstructions.pdf';
    }

    /**
     * Returns the URL for the observer Skype page.
     *
     * @return string
     */
    public static function getObserverSkype(): string
    {
        return '/observing/computer/skype.php';
    }

    /**
     * Returns the URL for the communication options page.
     *
     * @return string
     */
    public static function getCommunicationOptions(): string
    {
        return '/observing/computer/communications.php';
    }

    /**
     * Returns the URL for the Video Conference page.
     *
     * @return string
     */
    public static function getVideoConference(): string
    {
        return '/observing/computer/video_conference.php';
    }

    /**
     * Returns the URL for the service observing page.
     *
     * @return string
     */
    public static function getServiceObserving(): string
    {
        return '/observing/computer/service.php';
    }

    // Observing: Safety

    /**
     * Returns the URL for the safety index page.
     *
     * @return string
     */
    public static function getSafetyIndex(): string
    {
        return '/safety/index.php';
    }

    /**
     * Returns the URL for the IRTF Observers section in the safety page.
     *
     * @return string
     */
    public static function getSafetyObservers(): string
    {
        return '/safety/index.php#IRTFObservers';
    }

    /**
     * Returns the URL for the safety procedures section.
     *
     * @return string
     */
    public static function getSafetyProcedures(): string
    {
        return '/safety/index.php#Procedures';
    }

    /**
     * Returns the URL for the Risk and Release section.
     *
     * @return string
     */
    public static function getSafetyRiskAndRelease(): string
    {
        return '/safety/index.php#RiskAndRelease';
    }

    /**
     * Returns the URL for the archived safety information section.
     *
     * @return string
     */
    public static function getSafetyArchived(): string
    {
        return '/safety/index.php#Archived';
    }

    /**
     * Returns the URL for the IRTF Safety Regulations PDF.
     *
     * @return string
     */
    public static function getSafetyRegulations(): string
    {
        return '/safety/IRTF_Safety_Regulations.pdf';
    }

    /**
     * Returns the URL for the MKSS Winter Precautions PDF.
     *
     * @return string
     */
    public static function getWinterPrecautions(): string
    {
        return '/safety/MKSS_Winter_Precautions.pdf';
    }

    /**
     * Returns the URL for the Driving Recommendations PDF.
     *
     * @return string
     */
    public static function getDrivingRecommendations(): string
    {
        return '/safety/Driving_Recommendations.pdf';
    }

    /**
     * Returns the URL for the Evacuation Procedure PDF.
     *
     * @return string
     */
    public static function getEvacuationProcedure(): string
    {
        return '/safety/Evacuation_Procedure.pdf';
    }

    /**
     * Returns the URL for the Mauna Kea Hazards PDF.
     *
     * @return string
     */
    public static function getMaunaKeaHazards(): string
    {
        return '/safety/MK_Hazards.pdf';
    }

    /**
     * Returns the URL for the Winter Hazards PDF.
     *
     * @return string
     */
    public static function getWinterHazards(): string
    {
        return '/safety/Winter_Hazards.pdf';
    }

    /**
     * Returns the URL for the Risk and Release Form PDF.
     *
     * @return string
     */
    public static function getRiskReleaseForm(): string
    {
        return '/safety/Risk_Release_Form.pdf';
    }

    /**
     * Returns the URL for the Health Cautions Form PDF.
     *
     * @return string
     */
    public static function getHealthCautionsForm(): string
    {
        return '/safety/Health_Cautions_Form.pdf';
    }

    /**
     * Returns the URL for the Summit Information PDF.
     *
     * @return string
     */
    public static function getSummitInformation(): string
    {
        return '/safety/Summit_Information.pdf';
    }

    /**
     * Returns the URL for the Safe and Enjoyable Trip PDF.
     *
     * @return string
     */
    public static function getSafeEnjoyableTrip(): string
    {
        return '/safety/Safe_and_Enjoyable_Trip.pdf';
    }

    /**
     * Returns the URL for the MKSS Safety Info (2013) page.
     *
     * @return string
     */
    public static function getMKSSSafetyInfo(): string
    {
        return '/safety/MKSS_safety_info_2013/';
    }

    // Observing: Transportation

    /**
     * Returns the URL for the vehicle information page.
     *
     * @return string
     */
    public static function getVehicleInformation(): string
    {
        return '/observing/transportation/information.php';
    }

    /**
     * Returns the URL for the vehicle schedule text file.
     *
     * @return string
     */
    public static function getVehicleSchedule(): string
    {
        return '/Keybox/vehicle.txt';
    }

    /**
     * Returns the URL for the accident reporting PDF.
     *
     * @return string
     */
    public static function getAccidentReporting(): string
    {
        return '/observing/transportation/Accident_reporting.pdf';
    }

    // Observing: Weather

    /**
     * Returns the URL for the Quick Look page.
     *
     * @return string
     */
    public static function getWeatherQuickLook(): string
    {
        return '/weather/quicklook.php';
    }

    /**
     * Returns the URL for the Visible All Sky page.
     *
     * @return string
     */
    public static function getVisibleAllSky(): string
    {
        return '/weather/allsky.php';
    }

    /**
     * Returns the URL for the Infrared All Sky page at CFHT.
     *
     * @return string
     */
    public static function getInfraredAllSky(): string
    {
        return 'http://www.cfht.hawaii.edu/~asiva';
    }

    /**
     * Returns the URL for the main weather page.
     *
     * @return string
     */
    public static function getWeather(): string
    {
        return '/weather/index.php';
    }

    /**
     * Returns the URL for the IRTF Local Weather Pages.
     *
     * @return string
     */
    public static function getIRTFWeather(): string
    {
        return '/weather/IRTFLocalPages.php';
    }

    /**
     * Returns the URL for the Mauna Kea Weather Center (external link).
     *
     * @return string
     */
    public static function getMKWC(): string
    {
        return 'http://mkwc.ifa.hawaii.edu/';
    }

    /**
     * Returns the URL for the IFA weather page.
     *
     * @return string
     */
    public static function getIFAWeather(): string
    {
        return 'http://www.ifa.hawaii.edu/info/front_page_news/weather.shtml';
    }

    /**
     * Returns the URL for the 88-inch telescope All Sky page.
     *
     * @return string
     */
    public static function get88inAllSky(): string
    {
        return 'http://kree.ifa.hawaii.edu/allsky';
    }

    /**
     * Returns the URL for the Gemini Cloud Camera page.
     *
     * @return string
     */
    public static function getGeminiCloudCam(): string
    {
        return 'https://www.gemini.edu/sciops/telescopes-and-sites/weather/mauna-kea/cloud-cam';
    }

    /**
     * Returns the URL for the CFHT homepage.
     *
     * @return string
     */
    public static function getCFHTHomepage(): string
    {
        return 'http://www.cfht.hawaii.edu';
    }

    /**
     * Returns the URL for the ASIVA infrared data at CFHT.
     *
     * @return string
     */
    public static function getASIVA(): string
    {
        return 'http://www.cfht.hawaii.edu/~asiva';
    }

    /**
     * Returns the URL for the CFHT cloud camera gallery.
     *
     * @return string
     */
    public static function getCFHTCloudCams(): string
    {
        return 'http://www.cfht.hawaii.edu/en/gallery/cloudcams';
    }

    /**
     * Returns the URL for the Keck homepage.
     *
     * @return string
     */
    public static function getKeckHomepage(): string
    {
        return 'http://www2.keck.ifa.hawaii.edu';
    }

    /**
     * Returns the URL for the Keck cloud camera 1 page.
     *
     * @return string
     */
    public static function getKeckCloudCam1(): string
    {
        return 'http://www2.keck.hawaii.edu/software/weather';
    }

    /**
     * Returns the URL for the Keck cloud camera 2 page.
     *
     * @return string
     */
    public static function getKeckCloudCam2(): string
    {
        return 'http://www2.keck.hawaii.edu/realtime/webcam';
    }

    // IR Reference Data (IRrefdata)

    /**
     * Returns the URL for the IR reference data page.
     *
     * @return string
     */
    public static function getIRReferenceData(): string
    {
        return '/IRrefdata';
    }

    /**
     * Returns the URL for the telescope reference data page.
     *
     * @return string
     */
    public static function getTelescopeReferenceData(): string
    {
        return '/IRrefdata/telescope_ref_data.php';
    }

    /**
     * Returns the URL for the photometric catalogs page.
     *
     * @return string
     */
    public static function getPhotometricCatalogs(): string
    {
        return '/IRrefdata/ph_catalogs.php';
    }

    /**
     * Returns the URL for the spectral catalogs page.
     *
     * @return string
     */
    public static function getSpectralCatalogs(): string
    {
        return '/IRrefdata/sp_catalogs.php';
    }

    /**
     * Returns the URL for the daytime sky background page.
     *
     * @return string
     */
    public static function getDaytimeSkyBackground(): string
    {
        return '/IRrefdata/day_sky_bkgrnd.php';
    }

    /**
     * Returns the URL for the IWA FDV page.
     *
     * @return string
     */
    public static function getIWAFDV(): string
    {
        return '/IRrefdata/iwafdv.html';
    }

    /**
     * Returns the URL for the UKIRT astronomy page.
     *
     * @return string
     */
    public static function getUKIRT(): string
    {
        return 'http://www.ukirt.hawaii.edu/astronomy';
    }

    // Research

    /**
     * Returns the URL for the research resource listing.
     *
     * @return string
     */
    public static function getResearch(): string
    {
        return '/research/';
    }

    /**
     * Returns the URL for the IRTF acknowledgment page.
     *
     * @return string
     */
    public static function getResearchAcknowledgment(): string
    {
        return '/research/acknowledge.php';
    }

    /**
     * Returns the URL for the awarded time page.
     *
     * @return string
     */
    public static function getAwardedTime(): string
    {
        return '/research/awarded_time.php';
    }

    /**
     * Returns the URL for the science highlights page.
     *
     * @return string
     */
    public static function getScienceHighlightsPage(): string
    {
        return '/research/science.php';
        //return '/~proposal/research-2/all-highlights.php';
    }

    /**
     * Returns the URL for the data reduction resources page.
     *
     * @return string
     */
    public static function getDataReductionResources(): string
    {
        return '/research/dr_resources';
    }

    /**
     * Returns the URL for the FREIA project page.
     *
     * @return string
     */
    public static function getFreiaProject(): string
    {
        return '/research/freia/freia.php';
    }

    /**
     * Returns the URL for the bibliographic publications include file.
     *
     * @return string
     */
    public static function getBiblioInclude(): string
    {
        return '/htdocs/research/biblio/publications.inc';
    }

    /**
     * Returns the URL for the bibliographic publications (development) include file.
     *
     * @return string
     */
    public static function getBiblioIncludeDev(): string
    {
        return '/htdocs/research/biblio/publications_DEV.inc';
    }

    /**
     * Returns the URL for the bibliographic publications PHP file.
     *
     * @return string
     */
    public static function getBiblioPHP(): string
    {
        return '/htdocs/research/biblio/publications.php';
    }

    /**
     * Returns the URL for the bibliographic publications (development) PHP file.
     *
     * @return string
     */
    public static function getBiblioPHPDev(): string
    {
        return '/htdocs/research/biblio/publications_DEV.php';
    }

    /**
     * Returns the URL for the IRTF bibliographic search on ADS.
     *
     * @return string
     */
    public static function getBibliography(): string
    {
        return 'https://ui.adsabs.harvard.edu/search/q=bibgroup%3A%22irtf%22&sort=date%20desc%2C%20bibcode%20desc&p_=0';
    }

    /**
     * Returns the URL for the non-refereed bibliography.
     *
     * @return string
     */
    public static function getNonRefereedBibliography(): string
    {
        return '/research/biblio/Non_Refereed.html';
    }

    /**
     * Returns the URL for the dissertations bibliography.
     *
     * @return string
     */
    public static function getDissertationsBibliography(): string
    {
        return '/research/biblio/dissertations.html';
    }

    /**
     * Returns the URL for the IRSA data archive page.
     *
     * @return string
     */
    public static function getArchiveAtIRSA(): string
    {
        return 'https://irsa.ipac.caltech.edu/Missions/irtf.html';
    }

    /**
     * Returns the URL for the IRTF data archive page.
     *
     * @return string
     */
    public static function getIRTFDataArchive(): string
    {
        return 'http://irtfweb.ifa.hawaii.edu/research/irtf_data_archive.php';
    }

    /**
     * REMOVE THIS BLOCK ONCE ALL THE ACCOUNTS IN THIS SECTION HAVE BEEN RELOCATED
     *
     * SOME OF THESE ACCOUNTS NEED TO BE RELOCATED TO THE CORRECT DATASET
     *
     * for the active instrumentation accounts (projobs)
     * [these are project accounts associated directly with specific instruments]
     *
     * Accounts:
     *
     * - felix    - felix instrument (under development)
     *
     * - ishell   - ishell instrument (project)
     * - cartman  - (computer)
     * - kenny    - (computer)
     * - kyle     - (computer)
     *
     * - mirsi    - mirsi instrument (project/computer)
     * - moc      - mirsi's moc instrument (project/computer)
     *
     * - opihi    - opihi instrument (project/computer)
     *
     * - smokey   - smokey instrument (project/computer); will be retired after felix is fully online
     *
     * - spectre  - spectre instrument (under development)
     *
     * - spex     - spex instrument (project)
     * - bigdog   - (computer)
     * - guidedog - (computer)
     * - ldog     - (computer)
     * - moris    - spex' moris instrument (project/computer)
     *
     * - tcs3     - tcs3 telescope control system (project)
     *
     * - texes    - texes visitor instrument (project)
     */

    // Projects: Instrumentation (active) [/aux2/summit/home/projobs/]

    /**
     * Returns the URL for the FELIX project.
     *
     * @return string
     */
    public static function getFelix(): string
    {
        return '/~felix';
    }

    /**
     * Returns the URL for the iSHELL project.
     *
     * @return string
     */
    public static function getIshell(): string
    {
        return '/~ishell';
    }

    /**
     * Returns the URL for the iSHELL observing manual PDF.
     *
     * @return string
     */
    public static function getIshellDocs(): string
    {
        return '/~ishell/iSHELL_observing_manual.pdf';
    }

    /**
     * Returns the URL for the MIRSI project hosted by the Harvard-Smithsonian Center for Astrophysics.
     *
     * @return string
     */
    public static function getMirsiCfa(): string
    {
        return 'http://www.cfa.harvard.edu/mirsi';
    }

    /**
     * Returns the URL for the MIRSI project.
     *
     * @return string
     */
    public static function getMirsi(): string
    {
        return '/~mirsi';
    }

    /**
     * Returns the URL for the MIRSI Call for Proposals for semester 2022A.
     *
     * @return string
     */
    public static function getMirsiCfP2022A(): string
    {
        return '/~mirsi/MIRSI_Call_for_Proposals2022A.pdf';
    }

    /**
     * Returns the URL for the MOC project.
     *
     * @return string
     */
    public static function getMoc(): string
    {
        return '/~moc';
    }

    /**
     * Returns the URL for the MORIS project.
     *
     * @return string
     */
    public static function getMoris(): string
    {
        return '/~moris';
    }

    /**
     * Returns the URL for the `OPIHI project.
     *
     * @return string
     */
    public static function getOpihi(): string
    {
        return '/~opihi';
    }

    /**
     * Returns the URL for the SMOKEY project.
     *
     * @return string
     */
    public static function getSmokey(): string
    {
        return '/~smokey';
    }

    /**
     * Returns the URL for the SPECTRE project.
     *
     * @return string
     */
    public static function getSpectre(): string
    {
        return '/~spectre';
    }

    /**
     * Returns the URL for the SpeX project.
     *
     * @return string
     */
    public static function getSpex(): string
    {
        return '/~spex';
    }

    /**
     * Returns the URL for the SpeX Spectral Source Library.
     *
     * @return string
     */
    public static function getSpexSource(): string
    {
        return '/Facility/spectra_source/';
    }

    /**
     * Returns the URL for the internal SpeX resources.
     *
     * @return string
     */
    public static function getSpexInternal(): string
    {
        return '/~spex/internal';
    }

    /**
     * Returns the URL to download the Spextool version 4.1.
     *
     * @return string
     */
    public static function getSpexTool(): string
    {
        return '/~spex/Spextool_v4.1.tar.gz';
    }

    /**
     * Returns the URL to download the SpeX data file.
     *
     * @return string
     */
    public static function getSpexToolData(): string
    {
        return '/~spex/uSpeXdata.tar.gz';
    }

    /**
     * Returns the URL for the SpeX Spectral Library.
     *
     * @return string
     */
    public static function getSpexSpectralLibrary(): string
    {
        return '/~spex/IRTF_Spectral_Library';
    }

    /**
     * Returns the URL for the SpeX Spectral Library references page.
     *
     * @return string
     */
    public static function getSpexSpectralReferences(): string
    {
        return '/~spex/IRTF_Spectral_Library/References.html';
    }

    /**
     * Returns the URL for the IRTF Extended Spectral Library.
     *
     * @return string
     */
    public static function getSpexExtendedSpectralLibrary(): string
    {
        return '/~spex/IRTF_Extended_Spectral_Library';
    }

    /**
     * Returns the URL for the SpeX Prism Library hosted by UCSD.
     *
     * @return string
     */
    public static function getSpexPrismLibrary(): string
    {
        return 'http://pono.ucsd.edu/~adam/browndwarfs/spexprism/library.html';
    }

    /**
     * Returns the URL for the SpeX startup/shutdown procedures.
     *
     * @return string
     */
    public static function getSpexStartupShutdown(): string
    {
        return '/~spex/work/startup_shutdown/startup_shutdown.html';
    }

    /**
     * Returns the URL for the TCS3 project.
     *
     * @return string
     */
    public static function getTcs3(): string
    {
        return '/~tcs3';
    }

    /**
     * Returns the URL for the Starcat related resources for TCS3.
     *
     * @return string
     */
    public static function getStarcat(): string
    {
        return '/~tcs3/related/starcat';
    }

    /**
     * Returns the URL for the T3Remote manual for TCS3.
     *
     * @return string
     */
    public static function getT3RemoteManual(): string
    {
        return '/~tcs3/tcs3/users_manuals/1102_t3remote.pdf';
    }

    /**
     * Returns the URL for the TCS3 user manuals.
     *
     * @return string
     */
    public static function getTcs3UserManuals(): string
    {
        return '/~tcs3/tcs3/users_manuals/';
    }

    /**
     * Returns the URL for the TEXES project.
     *
     * @return string
     */
    public static function getTexes(): string
    {
        return '/~texes/';
    }

    /**
     * REMOVE THIS BLOCK ONCE ALL THE ACCOUNTS IN THIS SECTION HAVE BEEN RELOCATED
     *
     * SOME OF THESE ACCOUNTS NEED TO BE RELOCATED TO THE CORRECT DATASET
     *
     * for the development versions of the active instrumentation accounts (projdev)
     * [these are project accounts associated directly with specific instruments]
     *
     * Accounts:
     *
     * - bd64     - spex instrument dev (64bit computer)
     * - cartman2 - ishell instrument dev (computer)
     * - gd64     - spex instrument dev (64bit computer)
     * - kyle2    - ishell instrument dev (computer)
     * - ld64     - spex instrument dev (64bit computer)
     * - m2       - mirsi instrument dev (project/computer)
     * - opihi2   - opihi instrument dev (project/computer)
     * - s2       - spex instrument dev (project)
     * - smokey2  - smokey instrument dev (project/computer); will be retired after felix is fully online
     * - spectre2 - spectre instrument dev (under development)
     */

    // Projects: Instrumentation (development) [/aux2/summit/home/projdev/]

    /**
     * Returns the URL for the MIRSI2 project.
     *
     * @return string
     */
    public static function getMirsi2(): string
    {
        return '/~m2';
    }

    /**
     * Returns the URL for the SpeX2 project.
     *
     * @return string
     */
    public static function getSpex2(): string
    {
        return '/~s2';
    }

    /**
     * REMOVE THIS BLOCK ONCE ALL THE ACCOUNTS IN THIS SECTION HAVE BEEN RELOCATED
     *
     * SOME OF THESE ACCOUNTS NEED TO BE RELOCATED TO THE CORRECT DATASET
     *
     * for the active observation-supporting accounts (projfac1)
     * [these are project accounts that directly support observing]
     *
     * Accounts:
     *
     * - coolracks
     * - fct
     * - iqup
     * - quicklook
     * - t3remote
     * - vnc
     */

    // Projects: Observing Support (active) [/aux2/summit/home/projfac1/]

    /**
     * Returns the URL for the Autofocus project.
     *
     * @return string
     */
    public static function getAutofocus(): string
    {
        return '/~fct';
    }

    /**
     * Returns the URL for the Coolracks project.
     *
     * @return string
     */
    public static function getCoolracks(): string
    {
        return '/~coolracks';
    }

    /**
     * Returns the URL for the Iqup project.
     *
     * @return string
     */
    public static function getIqup(): string
    {
        return '/~iqup';
    }

    /**
     * Returns the URL for the Iqup temperature monitoring page.
     *
     * @return string
     */
    public static function getIqupTemps(): string
    {
        return '/~iqup/domeenv/dome.html';
    }

    /**
     * Returns the URL for the Iqup HVAC system.
     *
     * @return string
     */
    public static function getIqupHVAC(): string
    {
        return '/~iqup/hvac';
    }

    /**
     * Returns the URL for the HVAC system project (2014).
     *
     * @return string
     */
    public static function getHVAC2014(): string
    {
        return '/Facility/2014_hvac/';
    }

    /**
     * Returns the URL for the Quick Look project.
     *
     * @return string
     */
    public static function getQuickLook(): string
    {
        return '/~quicklook';
    }

    /**
     * Returns the URL for the VNC project.
     *
     * @return string
     */
    public static function getVNC(): string
    {
        return '/~vnc';
    }

    /**
     * Returns the URL for the IRTF Power Monitor.
     *
     * @return string
     */
    public static function getIRTFPowerMonitor(): string
    {
        return 'http://irtfpowermonitor.ifa.hawaii.edu/';
    }
    // Instruments

    /**
     * Returns the URL for facility instruments page.
     *
     * @return string
     */
    public static function getFacilityInstruments(): string
    {
        return '/instruments';
    }

    /**
     * Returns the URL for visitor instruments section.
     *
     * @return string
     */
    public static function getVisitorInstruments(): string
    {
        return '/instruments/#Visitor';
    }

    /**
     * Returns the URL for retired instruments page.
     *
     * @return string
     */
    public static function getRetiredInstruments(): string
    {
        return '/instruments/retiredInstruments.php';
    }

    /**
     * REMOVE THIS BLOCK ONCE ALL THE ACCOUNTS IN THIS SECTION HAVE BEEN RELOCATED
     *
     * SOME OF THESE ACCOUNTS NEED TO BE RELOCATED TO THE CORRECT DATASET
     *
     * for the miscellaneous non-observation-supporting project accounts (projfac2)
     * [these are project accounts that support the facility but not observing]
     *
     * Accounts:
     *
     * - addguest
     * - ida
     * - irtfadm
     * - irtfcameras
     * - proposal
     * - sourceplot
     * - to
     * - trouble
     * - ups
     * - webdev2024
     * - webmastr
     */

    // Projects: Facility Support (active) [/aux2/summit/home/projfac2/]

    /**
     * Returns the URL for the IRTF Cameras project.
     *
     * @return string
     */
    public static function getIRTFCameras(): string
    {
        return '/~irtfcameras';
    }

    /**
     * Returns the URL for the Webcams project.
     *
     * @return string
     */
    public static function getWebcams(): string
    {
        return self::getIRTFCameras();
    }

    /**
     * Returns the URL for IRTF Camera Documents.
     *
     * @return string
     */
    public static function getIRTFCameraDocs(): string
    {
        return '/~irtfcameras/irtf';
    }

    /**
     * Returns the URL for the WebCam Documents.
     *
     * @return string
     */
    public static function getWebCamDocs(): string
    {
        return self::getIRTFCameraDocs();
    }

    /**
     * Returns the URL for the Trouble project.
     *
     * @return string
     */
    public static function getTrouble(): string
    {
        return '/~trouble';
    }

    /**
     * Returns the URL for the Troublelog page.
     *
     * @return string
     */
    public static function getTroubleLog(): string
    {
        return '/irtf/troublelog/troublelog.php';
    }

    // General: Facility Support

    /**
     * Returns the URL for the Facility homepage.
     *
     * @return string
     */
    public static function getFacility(): string
    {
        return '/Facility';
    }

    /**
     * Returns the URL for Facility Communications section.
     *
     * @return string
     */
    public static function getFacilityCommunications(): string
    {
        return '/Facility#Communications';
    }

    /**
     * Returns the URL for Facility Schedules section.
     *
     * @return string
     */
    public static function getFacilitySchedules(): string
    {
        return '/Facility#Schedules';
    }

    /**
     * Returns the URL for Facility Systems section.
     *
     * @return string
     */
    public static function getFacilitySystems(): string
    {
        return '/Facility/#Systems';
    }

    /**
     * Returns the URL for Facility Offsite section.
     *
     * @return string
     */
    public static function getFacilityOffsite(): string
    {
        return '/Facility/#Offsite';
    }

    /**
     * Returns the URL for Facility Archived Instruments.
     *
     * @return string
     */
    public static function getFacilityArchived(): string
    {
        return '/Facility/retiredInstruments.php';
    }

    /**
     * Returns the URL for the MIM project.
     *
     * @return string
     */
    public static function getMIM(): string
    {
        return '/Facility/MIM';
    }

    /**
     * Returns the URL for the BASS remote sensing project.
     *
     * @return string
     */
    public static function getBass(): string
    {
        return 'http://www.aero.org/capabilities/remotesensing/bass.html';
    }

    /**
     * Returns the URL for the HIPWAC project page.
     *
     * @return string
     */
    public static function getHipwac(): string
    {
        return 'https://ssed.gsfc.nasa.gov/hipwac/researchers.html';
    }

    /**
     * Returns the URL for the Celeste project.
     *
     * @return string
     */
    public static function getCeleste(): string
    {
        return 'http://celeste';
    }

    /**
     * Returns the URL for the Dome Vents project.
     *
     * @return string
     */
    public static function getDomeVents(): string
    {
        return '/Facility/dome_vents';
    }

    /**
     * Returns the URL for Facility Phones section.
     *
     * @return string
     */
    public static function getFacilityPhones(): string
    {
        return '/Facility/phones';
    }

    /**
     * Returns the URL for Facility Weather section.
     *
     * @return string
     */
    public static function getFacilityWeather(): string
    {
        return '/Facility/weather';
    }

    /**
     * Returns the URL for Facility Table of Contents.
     *
     * @return string
     */
    public static function getFacilityTOC(): string
    {
        return '/Facility/facilityTOC.php';
    }

    /**
     * Returns the URL for the Tip Tilt project.
     *
     * @return string
     */
    public static function getTipTilt(): string
    {
        return '/Facility/tiptilt/';
    }

    /**
     * Returns the URL for the Photometers project.
     *
     * @return string
     */
    public static function getPhotometers(): string
    {
        return '/Facility/photometers/';
    }

    /**
     * Returns the URL for the XGFit project.
     *
     * @return string
     */
    public static function getXGFit(): string
    {
        return '/Facility/xgfit/';
    }

    /**
     * Returns the URL for the New DAS project.
     *
     * @return string
     */
    public static function getNewDAS(): string
    {
        return '/Facility/NewDAS/NewDAS.html';
    }

    /**
     * Returns the URL for the Cranes section.
     *
     * @return string
     */
    public static function getCranes(): string
    {
        return '/Facility/cranes';
    }

    /**
     * Returns the URL for the DV project homepage.
     *
     * @return string
     */
    public static function getDV(): string
    {
        return '/Facility/DV/index.php';
    }

    /**
     * Returns the URL for the DV User Guide.
     *
     * @return string
     */
    public static function getDVGuide(): string
    {
        return '/Facility/DV/dv_userguide.pdf';
    }

    /**
     * Returns the URL for the DV Cheatsheet.
     *
     * @return string
     */
    public static function getDVCheatsheet(): string
    {
        return '/Facility/DV/dv_cheatsheet_v0.pdf';
    }

    // Proposals-related URLs

    /**
     * Returns the URL for the Accounts List page in Proposals.
     *
     * @return string
     */
    public static function getAccountsList(): string
    {
        return '/~proposal/accounts/ListAccounts.php';
    }

    /**
     * Returns the URL for the List Applications page in Proposals.
     *
     * @return string
     */
    public static function getListApplications(): string
    {
        return '/~proposal/applications/ListApplications.php';
    }

    /**
     * Returns the URL for the Edit Applications page in Proposals.
     *
     * @return string
     */
    public static function getEditApplications(): string
    {
        return '/~proposal/applications/EditApplications.php';
    }

    /**
     * Returns the URL for the Process Applications page in Proposals.
     *
     * @return string
     */
    public static function getProcessApplications(): string
    {
        return '/~proposal/applications/ProcessApplications.php';
    }

    /**
     * Returns the URL for the Proposal Procedures page.
     *
     * @return string
     */
    public static function getProposalProcedures(): string
    {
        return '/~proposal/documentation';
    }

    /**
     * Returns the URL for the IRTF Staff page in Proposals.
     *
     * @return string
     */
    public static function getIRTFStaff(): string
    {
        return '/~proposal/documentation/irtfstaff.php';
    }

    /**
     * Returns the URL for the schedule file importer.
     *
     * @return string
     */
    public static function getUploadSchedule(): string
    {
        return '/~proposal/schedule/UploadScheduleFile.php';
    }

    /**
     * Returns the URL for the tac results exporter.
     *
     * @return string
     */
    public static function getExportTACResults(): string
    {
        return '/~proposal/schedule/ExportTACResults.php';
    }

    /**
     * Returns the URL for the tac results importer.
     *
     * @return string
     */
    public static function getUploadTACResults(): string
    {
        return '/~proposal/schedule/UploadTACResults.php';
    }

    /**
     * Returns the URL for the schedule generator.
     *
     * @return string
     */
    public static function getGenerateSchedule(): string
    {
        return '/~proposal/schedule/GenerateSchedule.php';
    }

    /**
     * Returns the URL for the support astronomer database editor.
     *
     * @return string
     */
    public static function getEditSupportAstromers(): string
    {
        return '/~proposal/schedule/EditSupportAstromersDB.php';
    }

    /**
     * Returns the URL for the operators database editor.
     *
     * @return string
     */
    public static function getEditOperators(): string
    {
        return '/~proposal/schedule/EditOperatorsDB.php';
    }

    /**
     * Returns the URL for the instruments database editor.
     *
     * @return string
     */
    public static function getEditInstruments(): string
    {
        return '/~proposal/schedule/EditInstrumentsDB.php';
    }

    /**
     * Returns the URL for the engineering program editor.
     *
     * @return string
     */
    public static function getEditEngPrograms(): string
    {
        return '/~proposal/schedule/EditEngPrograms.php';
    }

    /**
     * Returns the URL for the TAC homepage.
     *
     * @return string
     */
    public static function getTAC(): string
    {
        return '/~proposal/tac/tac.php';
    }

    /**
     * Returns the URL for the TAC index page.
     *
     * @return string
     */
    public static function getTACIndex(): string
    {
        return '/~proposal/tac/tac.php?s=index';
    }

    /**
     * Returns the URL for the TAC page for a specific semester.
     *
     * @param string $sem The semester code (e.g., "2023A").
     * @return string
     */
    public static function getTACSemester(string $sem): string
    {
        return "/~proposal/tac/tac.php?s={$sem}";
    }

    /**
     * Returns the URL for the Data Request page.
     *
     * @return string
     */
    public static function getDataRequest(): string
    {
        return '/~proposal/datarequest';
    }

    /**
     * Returns the URL for the Science Highlights Upload page.
     *
     * @return string
     */
    public static function getScienceHighlightsUpload(): string
    {
        return '/~proposal/sciencehighlights';
    }

    /**
     * REMOVE THIS BLOCK ONCE ALL THE ACCOUNTS IN THIS SECTION HAVE BEEN RELOCATED
     *
     * SOME OF THESE ACCOUNTS NEED TO BE RELOCATED TO THE CORRECT DATASET
     *
     * for the retired instrumentation accounts, old support accounts (archives_projects)
     * [these are project accounts associated directly with retired instruments or
     * old support accounts that are no longer used]
     *
     * Accounts:
     *
     * - ao
     * - apogee
     * - autofocu
     * - bender
     * - cshell
     * - guider
     * - iarc
     * - iarc.20121210
     * - iarc.20121212_wonky
     * - iarcal
     * - iarch2rg
     * - iarch4rg
     * - mirac
     * - mirlin
     * - nsf2sw
     * - nsfcam
     * - nsfcam2
     * - oldbigdog
     * - oldguidedog
     * - phcs
     * - poets
     * - irtfdas
     * - sgir
     * - shoc
     * - tcs
     * - tcs1
     * - tiptilt
     * - tt
     * - 0403_du
     * - apache
     * - beachside
     * - checkin
     * - csrs
     * - cvs
     * - database
     * - domeres
     * - go
     * - guesttest
     * - idev
     * - iqup.bak
     * - iradmin
     * - iraf
     * - ircamera
     * - ircamera.201105.tar
     * - irgoogle
     * - ison
     * - locklist
     * - nobody
     * - onakaspam
     * - service
     * - tac
     * - villaume
     * - wirtanen
     */

    // Projects: Retired Facility Support or Instruments [/aux2/summit/home/archives_projects/]

    /**
     * Returns the URL for the AO (Adaptive Optics) project.
     *
     * @return string
     */
    public static function getAo(): string
    {
        return '/~ao';
    }

    /**
     * Returns the URL for the APOGEE project.
     *
     * @return string
     */
    public static function getApogee(): string
    {
        return '/~apogee';
    }

    /**
     * Returns the URL for the Cshell project.
     *
     * @return string
     */
    public static function getCshell(): string
    {
        return '/~cshell';
    }

    /**
     * Returns the URL for the Cshell startup page.
     *
     * @return string
     */
    public static function getCshellStart(): string
    {
        return '/~cshell/start.html';
    }

    /**
     * Returns the URL for the original NSFCAM project.
     *
     * @return string
     */
    public static function getNsfcam(): string
    {
        return '/~nsfcam';
    }

    /**
     * Returns the URL for the NSFCAM2 project.
     *
     * @return string
     */
    public static function getNsfcam2(): string
    {
        return '/~nsfcam2/Welcome.html';
    }

    /**
     * Returns the URL for the internal NSFCAM2 project resources.
     *
     * @return string
     */
    public static function getNsfcam2Internal(): string
    {
        return '/~nsfcam2/internal';
    }

    /**
     * Returns the URL for the NSFCAM signal-to-noise calculator.
     *
     * @return string
     */
    public static function getNsfcamSNCalculator(): string
    {
        return '/cgi-bin/nsfcam/nsfcam_sncalc.cgi';
    }

    /**
     * Returns the URL for the NSFCAM magnitude calculator.
     *
     * @return string
     */
    public static function getNsfcamMagCalculator(): string
    {
        return '/cgi-bin/nsfcam/nsfcam_magcalc.cgi';
    }

    /**
     * Returns the URL for the NSFCAM time calculator.
     *
     * @return string
     */
    public static function getNsfcamTimeCalculator(): string
    {
        return '/cgi-bin/nsfcam/nsfcam_timecalc.cgi';
    }

    /**
     * Returns the URL for the NSFCAM filters page.
     *
     * @return string
     */
    public static function getNsfcamFilters(): string
    {
        return '/~nsfcam/hist/newfilters.html';
    }

    /**
     * Returns the URL for the NSFCAM Mauna Kea filters page.
     *
     * @return string
     */
    public static function getNsfcamMKFilters(): string
    {
        return '/~nsfcam/mkfilters.html';
    }

    /**
     * Returns the URL for the NSFCAM2 quick start guide.
     *
     * @return string
     */
    public static function getNsfcam2QuickStart(): string
    {
        return '/~nsfcam2/Quickstart.html';
    }

    /**
     * Returns the URL for the PHCS project.
     *
     * @return string
     */
    public static function getPhcs(): string
    {
        return '/~phcs';
    }

    /**
     * Returns the URL for the internal PHCS resources.
     *
     * @return string
     */
    public static function getPhcsInternal(): string
    {
        return '/~phcs/internal';
    }

    /**
     * Returns the URL for the POETS project.
     *
     * @return string
     */
    public static function getPoets(): string
    {
        return '/~poets';
    }

    /**
     * Returns the URL for the TCS1 project.
     *
     * @return string
     */
    public static function getTcs1(): string
    {
        return '/Facility/tcs1';
    }

    // Projects: General Projects

    /**
     * Returns the URL for Vern Stahlberger's Mechanical Data Archive Policy project.
     *
     * @return string
     */
    public static function getVSMAMechanical(): string
    {
        return '/~vern/Mechanical';
    }

    // Projects: ISON

    /**
     * Returns the URL for the ISON project homepage.
     *
     * @return string
     */
    public static function getISON(): string
    {
        return '/~ison';
    }

    // Galleries

    /**
     * Returns the URL for the Gallery Table of Contents.
     *
     * @return string
     */
    public static function getGalleryTOC(): string
    {
        return '/gallery/toc.php';
    }

    /**
     * Returns the URL for the main Gallery page.
     *
     * @return string
     */
    public static function getGallery(): string
    {
        return '/gallery/index.php';
    }

    /**
     * Returns the URL for the Gallery Staff page.
     *
     * @return string
     */
    public static function getGalleryStaff(): string
    {
        return '/gallery/staff.php';
    }

    /**
     * Returns the URL for the Gallery Night page.
     *
     * @return string
     */
    public static function getGalleryNight(): string
    {
        return '/irtf/night_gallery.php';
    }

    /**
     * Returns the URL for the Gallery Facility page.
     *
     * @return string
     */
    public static function getGalleryFacility(): string
    {
        return '/gallery/facility/';
    }

    /**
     * Returns the URL for the Gallery Tour page.
     *
     * @return string
     */
    public static function getGalleryTour(): string
    {
        $url = '"http://www.panaviz.com/scenic-hawaii/mauna-kea/nasa-irtf/bwdetect.html"';
        $javascript = "javascript:CreateWnd({$url},540,550,scrollbars=false);";
        return $javascript;
    }

    /**
     * Returns the URL for the User Gallery page.
     *
     * @return string
     */
    public static function getGalleryUser(): string
    {
        return '/gallery/user/';
    }

    /**
     * Returns the URL for the Facility Gallery page.
     *
     * @return string
     */
    public static function getGalleryFacilityOverview(): string
    {
        return '/gallery/facility';
    }

    // IRTF-Only: Internal Links

    /**
     * Returns the URL for the Google Drive link to OOA (Observatory Observing Assistant) information.
     *
     * @return string
     */
    public static function getOOAInfoDrive(): string
    {
        return 'https://drive.google.com/open?id=1895ax3z9FSjPvIfHnLGYUTYuSGRnzFr2m6htKRRndYo';
    }

    /**
     * Returns the URL for the Google Drive link to the Staff Zoom files.
     *
     * @return string
     */
    public static function getStaffZoomDrive(): string
    {
        return 'https://drive.google.com/drive/folders/1ImYOHzgahIPWbaSsjlByRimfa4i-0CTd?usp=sharing';
    }

    /**
     * Returns the URL for the IRTF Safety project.
     *
     * @return string
     */
    public static function getSafety(): string
    {
        return '/irtf/Safety/Safety.html';
    }

    /**
     * Returns the internal link for the Staff Zoom page.
     *
     * @return string
     */
    public static function getStaffZoom(): string
    {
        return '/irtf/zoom/zoom.php';
    }

    /**
     * Returns the internal link for the Staff Skype page.
     *
     * @return string
     */
    public static function getStaffSkype(): string
    {
        return '/irtf/skype/skype.php';
    }

    /**
     * Returns the link to the IRTF Twiki.
     *
     * @return string
     */
    public static function getTwiki(): string
    {
        return '/twiki';
    }

    /**
     * Returns the link to the IRTF-only page.
     *
     * @return string
     */
    public static function getIrtfOnly(): string
    {
        return '/irtf';
    }

    /**
     * Returns the link to the IRTF Benchmark Daily Log.
     *
     * @return string
     */
    public static function getIrtfOnlyBenchmark(): string
    {
        return '/irtf/benchmark/dailylog';
    }

    /**
     * Returns the link to the Observer Requirements Form (ORF) page.
     *
     * @return string
     */
    public static function getIrtfOnlyOrf(): string
    {
        return '/irtf/orf';
    }

    /**
     * Returns the link to the IRTF Wiki.
     *
     * @return string
     */
    public static function getIrtfOnlyWiki(): string
    {
        return '/irtf/wiki';
    }

    /**
     * Returns the link to the Technical Group Notes on the IRTF Wiki.
     *
     * @return string
     */
    public static function getIrtfOnlyTechNotes(): string
    {
        return '/irtf/wiki/index.php/Main/TechGroupNotes';
    }

    /**
     * Returns the link to the TO Schedule.
     *
     * @return string
     */
    public static function getTOSchedule(): string
    {
        return '/irtf/tosched';
    }

    /**
     * Returns the link to the Computing Documentation.
     *
     * @return string
     */
    public static function getComputingDocs(): string
    {
        return '/irtf/computing';
    }

    /**
     * Returns the link to the Network Documentation.
     *
     * @return string
     */
    public static function getNetworkDocs(): string
    {
        return '/irtf/computing/network';
    }

    /**
     * Returns the link to the Web Documentation.
     *
     * @return string
     */
    public static function getWebDocs(): string
    {
        return '/irtf/computing/webdocs';
    }

    /**
     * Returns the link to the Web Logs.
     *
     * @return string
     */
    public static function getWebLogs(): string
    {
        return '/irtf/computing/log';
    }

    /**
     * Returns the link to Network Policies.
     *
     * @return string
     */
    public static function getNetworkPolicies(): string
    {
        return '/irtf/computing/policies';
    }

    /**
     * Returns the link to the Email Notes page.
     *
     * @return string
     */
    public static function getEmailNotes(): string
    {
        return '/irtf/computing/info/email.php';
    }

    /**
     * Returns the link to the DVD Notes page.
     *
     * @return string
     */
    public static function getDVDNotes(): string
    {
        return '/irtf/computing/info/DVD.html';
    }

    /**
     * Returns the link to the Power On Recipe Notes.
     *
     * @return string
     */
    public static function getPowerOnNotes(): string
    {
        return '/irtf/computing/info/recipe.txt';
    }

    /**
     * Returns the link to the Mail Aliases page.
     *
     * @return string
     */
    public static function getMailAliases(): string
    {
        return '/irtf/aliases';
    }

    /**
     * Returns the link to the Administrative Procedures page.
     *
     * @return string
     */
    public static function getAdminProcedures(): string
    {
        return '/irtf/admin_procedures';
    }

    /**
     * Returns the link to the Apache Documentation.
     *
     * @return string
     */
    public static function getApacheDocs(): string
    {
        return '/manual/en';
    }

    /**
     * Returns the link to AWStats.
     *
     * @return string
     */
    public static function getAWStats(): string
    {
        return '/awstats/awstats.pl';
    }

    /**
     * Returns the link to Webalizer (web statistics).
     *
     * @return string
     */
    public static function getWebStats(): string
    {
        return '/irtf/webalizer';
    }

    /**
     * Returns the link to Cacti (monitoring).
     *
     * @return string
     */
    public static function getCacti(): string
    {
        return '/irtf/cacti';
    }

    /**
     * Returns the link to Fiber Documentation.
     *
     * @return string
     */
    public static function getFiberDocs(): string
    {
        return '/irtf/computing/network/fiber.php';
    }

    /**
     * Returns the link to KVM Documentation.
     *
     * @return string
     */
    public static function getKVMDcos(): string
    {
        return '/irtf/computing/network/kvm_201406.php';
    }

    /**
     * Returns the link to the KVM main interface.
     *
     * @return string
     */
    public static function getKVM(): string
    {
        return 'http://128.171.165.20';
    }

    /**
     * Returns the link to the TCS KVM interface.
     *
     * @return string
     */
    public static function getKVMtcs(): string
    {
        return 'http://128.171.165.20/hkc';
    }

    /**
     * Returns the link to the MIM KVM interface.
     *
     * @return string
     */
    public static function getKVMmim(): string
    {
        return 'http://128.171.165.24/hkc';
    }

    /**
     * Returns the link to the Coude KVM interface.
     *
     * @return string
     */
    public static function getKVMcoude(): string
    {
        return 'http://128.171.165.25/hkc';
    }

    /**
     * Returns the link to the UPS monitoring interface.
     *
     * @return string
     */
    public static function getUPS(): string
    {
        return 'http://128.171.165.54';
    }

    /**
     * Returns the link to WTI documentation.
     *
     * @return string
     */
    public static function getWTIDocs(): string
    {
        return '/irtf/computing/network/remotepwr.php';
    }

    /**
     * Returns the link to RPC documentation (same as WTI documentation).
     *
     * @return string
     */
    public static function getRPCDocs(): string
    {
        return self::getWTIDocs();
    }

    /**
     * Returns the link to the MIM WTI interface.
     *
     * @return string
     */
    public static function getMIMWTI(): string
    {
        return '/irtf/computing/network/remotepwr_coolracks.php';
    }

    /**
     * Returns the link to the TCS Room WTI interface.
     *
     * @return string
     */
    public static function getTCSRmWTI(): string
    {
        return 'http://128.171.165.27';
    }

    /**
     * Returns the link to TCS Room WTI documentation.
     *
     * @return string
     */
    public static function getTCSRmWTIDocs(): string
    {
        return '/irtf/computing/network/remotepwr_tcsrm.php';
    }

    /**
     * Returns the link to the Coude WTI interface.
     *
     * @return string
     */
    public static function getCoudeWTI(): string
    {
        return 'http://128.171.165.192:6876';
    }

    /**
     * Returns the HTTPS link to the Coude WTI interface.
     *
     * @return string
     */
    public static function getCoudeWTIhttps(): string
    {
        return 'https://128.171.165.192:6476';
    }

    /**
     * Returns the link to Coude WTI documentation.
     *
     * @return string
     */
    public static function getCoudeWTIDocs(): string
    {
        return '/irtf/computing/network/remotepwr_coude.php';
    }

    /**
     * Returns the link to the Hilo Server WTI interface.
     *
     * @return string
     */
    public static function getHiloSrvWTI(): string
    {
        return 'http://128.171.110.175:6875';
    }

    /**
     * Returns the HTTPS link to the Hilo Server WTI interface.
     *
     * @return string
     */
    public static function getHiloSrvWTIhttps(): string
    {
        return 'https://128.171.110.175:6475';
    }

    /**
     * Returns the link to Hilo Server WTI documentation.
     *
     * @return string
     */
    public static function getHiloSrvWTIDocs(): string
    {
        return '/irtf/computing/network/remotepwr_hilosrv.php';
    }

    /**
     * Returns the link to the VNC index page.
     *
     * @return string
     */
    public static function getVNCIndex(): string
    {
        return '/irtf/vnc_POTD/vnc.php';
    }

    /**
     * Returns the link to the VNC "Picture of the Day" (POTD) page.
     *
     * @return string
     */
    public static function getVNCPotD(): string
    {
        return '/irtf/vnc_POTD/index.html';
    }

    /**
     * Returns the link to the VNC instructions for CentOS 6 (RealVNC).
     *
     * @return string
     */
    public static function getSysVNC(): string
    {
        return '/irtf/computing/centos6/howtos/realvnc.php';
    }

    /**
     * Returns the link to the Iborg Plone instance.
     *
     * @return string
     */
    public static function getIborg(): string
    {
        return 'http://iborg.ifa.hawaii.edu:8080/Plone';
    }

    /**
     * Returns the link to the mirrored Plone site.
     *
     * @return string
     */
    public static function getPlone(): string
    {
        return '/irtf/plone-mirror/irtf_plone_2013.08.21/iborg.ifa.hawaii.edu_8080/Plone.html';
    }

    /**
     * Returns the link to the IRTF Google Drive.
     *
     * @return string
     */
    public static function getGDrive(): string
    {
        return 'https://sites.google.com/a/hawaii.edu/irtf-gdrive-gsite/';
    }

    /**
     * Returns the link to the Vehicle List page.
     *
     * @return string
     */
    public static function getVehicleList(): string
    {
        return '/irtf/contacts/vehicle_list.php';
    }

    /**
     * Returns the link to the Staff Phone List page.
     *
     * @return string
     */
    public static function getStaffPhoneList(): string
    {
        return '/irtf/contacts/staff_phone_list.php';
    }

    /**
     * Returns the link to the Night Staff Phone List page.
     *
     * @return string
     */
    public static function getNightStaffPhoneList(): string
    {
        return '/irtf/contacts/night_phone_list.php';
    }

    // End of IRTF-Only section

    // External

    /**
     * Returns the URL for the Institute for Astronomy (IfA) homepage.
     *
     * @return string
     */
    public static function getIfa(): string
    {
        return 'https://www.ifa.hawaii.edu';
    }

    /**
     * Returns the URL for the IfA calendar.
     *
     * @return string
     */
    public static function getIfaCalendar(): string
    {
        return 'https://home.ifa.hawaii.edu/ifa/calendar.htm';
    }

    /**
     * Returns the URL for the IfA personnel phone directory.
     *
     * @return string
     */
    public static function getIfaPhones(): string
    {
        return 'https://app.ifa.hawaii.edu/personnel';
    }

    /**
     * Returns the URL for the NEOSurvey webpage.
     *
     * @return string
     */
    public static function getNeosurvey(): string
    {
        return 'http://smass.mit.edu/minus.html';
    }

    // UH fiscal management office (FMO) URLs for WH-1

    /**
     * Returns the top-level URL for the UH fiscal management office's WH-1 section.
     *
     * @return string
     */
    public static function getUHFMOWH1Top(): string
    {
        return 'https://www.hawaii.edu/fmo/payment-reimbursement/forms-disbursing/';
    }

    /**
     * Returns the second top-level URL for the UH fiscal management office's WH-1 section.
     *
     * @return string
     */
    public static function getUHFMOWH1TopAlt(): string
    {
        return 'https://www.hawaii.edu/fmo/payment-reimbursement/forms-disbursing/';
    }

    /**
     * Returns the Google Drive URL for the WH-1 form.
     *
     * @return string
     */
    public static function getUHFMOWH1Form(): string
    {
        return 'https://drive.google.com/file/d/15ksQJRrQgO66klWvtYg5Cm2zT7EAT-U0/view';
    }

    /**
     * Returns an alternate Google Drive URL for the WH-1 form.
     *
     * @return string
     */
    public static function getUHFMOWH1FormAlt(): string
    {
        return 'https://drive.google.com/file/d/15ksQJRrQgO66klWvtYg5Cm2zT7EAT-U0/view';
    }

    /**
     * Returns the CDC guidelines URL for business response to COVID-19.
     *
     * @return string
     */
    public static function getCDCGuidelines(): string
    {
        return 'https://www.cdc.gov/coronavirus/2019-ncov/specific-groups/guidance-business-response.html';
    }

    /**
     * Returns the URL for the ClO (Chlorine Oxide) instrument on the NDACC website.
     *
     * @return string
     */
    public static function getClO(): string
    {
        return 'https://ndacc.larc.nasa.gov/instruments/microwave-radiometer';
    }
}
