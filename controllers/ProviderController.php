<?php
session_start();
require_once 'BaseController.php';
require_once 'models/ProviderModel.php';
require_once 'views/ProviderView.php';

/**
 * The ProviderController class is responsible for managing the provider page.
 */
class ProviderController extends BaseController
{
    /**
     * Displays the provider page.
     *
     * @return void
     */
    public function index()
    {
        if (isset($_GET['provider_id'])) {
            $providerId = $_SESSION['provider_id'] = $_GET['provider_id'];
        } else {
            echo "Error: provider_id is not set";
        }
        $providerModel = new ProviderModel();
        $children = $providerModel->getAllChildrenByProvider($providerId);
        $activeChildren = $providerModel->getAllActiveChildrenByProvider($providerId);
        $view = new ProviderView();
        $view->setData(['children' => $children, 'active_children' => $activeChildren]);
        $view->render();
    }

    /**
     * Updates the active status of a child.
     *
     * @return void
     */
    public function updateActiveStatus()
    {
        $providerId = $_SESSION['provider_id'] ?? null;
        $childId = $_POST['childId'] ?? null;
        $activeStatus = $_POST['activeStatus'] ?? null;

        $providerModel = new ProviderModel();
        $providerModel->updateChildActiveStatus($childId, $activeStatus);

        header('Location: index.php?page=provider&action=index&provider_id=' . $providerId);
    }

    /**
     * Adds a child to the provider.
     *
     * @return void
     */
    public function addChild()
    {
        $providerId = $_SESSION['provider_id'] ?? null;

        $firstName = isset($_POST['first_name']) ? $_POST['first_name'] : null;
        $lastName = isset($_POST['last_name']) ? $_POST['last_name'] : null;
        $startingDate = isset($_POST['starting_date']) ? $_POST['starting_date'] : null;
        $activeStatus = isset($_POST['active_status']) ? $_POST['active_status'] : null;
        $providerModel = new ProviderModel();
        $providerModel->insertChild($firstName, $lastName, $startingDate, $activeStatus, $providerId);

        header('Location: index.php?page=provider&action=index&provider_id=' . $providerId);

    }

    /**
     * Records attendance for the provider.
     *
     * @return void
     */
    public function recordAttendance()
    {
        $providerId = $_SESSION['provider_id'] ?? null;

        $mealDate = isset($_POST['meal_date']) ? $_POST['meal_date'] : null;
        $mealTime = isset($_POST['meal_time']) ? $_POST['meal_time'] : null;
        $containsFruit = isset($_POST['contains_fruit']) ? $_POST['contains_fruit'] : null;
        $containsVegetables = isset($_POST['contains_vegetables']) ? $_POST['contains_vegetables'] : null;
        $selectedChildren = isset($_POST['children']) ? $_POST['children'] : [];

        $providerModel = new ProviderModel();
        $providerModel->insertAttendance($providerId, $mealDate, $mealTime, $containsFruit, $containsVegetables, $selectedChildren);

        header('Location: index.php?page=provider&action=index&provider_id=' . $providerId);
    }

    /**
     * Generates a PDF of the provider attendance summary.
     *
     * @return void
     */
    public function attendanceSummaryPDF()
    {
        $providerId = $_SESSION['provider_id'] ?? null;
        $reportStartDate = isset($_POST['report_date']) ? $_POST['report_date'] : null;

        $providerModel = new ProviderModel();
        $summaryData = $providerModel->getProviderAttendanceSummary($providerId, $reportStartDate);
        $providerName = $providerModel->getProviderName($providerId)[0]['provider_name'];
        $mealTableData = $providerModel->getMealTable($providerId, $reportStartDate);
        // Include the PdfGenerator class
        require_once 'helpers/attendanceSummaryGenerator.php';

        // Call the generateAttendanceSummaryPDF method
        PdfGenerator::generateAttendanceSummaryPDF($summaryData, $providerName, $reportStartDate, $mealTableData);
        exit;
    }

    /**
     * Generates a PDF of an individual child's attendance summary.
     *
     * @return void
     */
    public function childAttendanceSummaryPDF()
    {
        $providerId = isset($_SESSION['provider_id']) ? $_SESSION['provider_id'] : null;
        $reportStartDate = isset($_POST['report_date']) ? $_POST['report_date'] : null;
        $childId = isset($_POST['child_id']) ? $_POST['child_id'] : null;

        $providerModel = new ProviderModel();
        $providerName = $providerModel->getProviderName($providerId)[0]['provider_name'];
        $summaryData = $providerModel->getIndividualAttendanceSummary($childId, $reportStartDate);

        require_once 'helpers/individualAttendanceSummaryGenerator.php';

        PdfGenerator::generateIndividualAttendanceSummaryPDF($summaryData, $providerName, $reportStartDate);
        exit;
    }

}