<?php
/**
 * API Router
 * HiraShine: Diamond Hisab Diary Backend API
 * Routes all API requests to appropriate controllers
 */

require_once __DIR__ . '/../config/config.php';

// Get request method and URI
$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = str_replace('/HiraBook/api', '', $uri); // Adjust based on your base path
$uri = trim($uri, '/');
$segments = explode('/', $uri);

// Route handling
$controller = $segments[0] ?? 'dashboard';
$action = $segments[1] ?? 'index';

try {
    switch ($controller) {
        case 'user':
            require_once __DIR__ . '/../controllers/UserController.php';
            $userController = new UserController();
            
            if ($action === 'initialize' && $method === 'POST') {
                $userController->initialize();
            } elseif ($action === 'profile' && $method === 'GET') {
                $userController->getProfile();
            } elseif ($action === 'profile' && $method === 'PUT') {
                $userController->updateProfile();
            } else {
                sendError("Invalid user endpoint", 404);
            }
            break;

        case 'dashboard':
            require_once __DIR__ . '/../controllers/DashboardController.php';
            $dashboardController = new DashboardController();
            
            if ($action === 'index' && $method === 'GET') {
                $dashboardController->getDashboard();
            } else {
                sendError("Invalid dashboard endpoint", 404);
            }
            break;

        case 'diamond-rate':
            require_once __DIR__ . '/../controllers/DiamondRateController.php';
            $rateController = new DiamondRateController();
            
            if ($action === 'add' && $method === 'POST') {
                $rateController->addRate();
            } elseif ($action === 'get' && $method === 'GET') {
                $rateController->getRate();
            } elseif ($action === 'update' && $method === 'PUT') {
                $rateController->updateRate();
            } elseif ($action === 'delete' && $method === 'DELETE') {
                $rateController->deleteRate();
            } else {
                sendError("Invalid diamond rate endpoint", 404);
            }
            break;

        case 'daily-entry':
            require_once __DIR__ . '/../controllers/DailyEntryController.php';
            $entryController = new DailyEntryController();
            
            if ($action === 'add' && $method === 'POST') {
                $entryController->addEntry();
            } elseif ($action === 'get' && $method === 'GET' && isset($segments[2])) {
                $entryController->getEntry();
            } elseif ($action === 'list' && $method === 'GET') {
                $entryController->getAllEntries();
            } elseif ($action === 'update' && $method === 'PUT') {
                $entryController->updateEntry();
            } elseif ($action === 'delete' && $method === 'DELETE') {
                $entryController->deleteEntry();
            } else {
                sendError("Invalid daily entry endpoint", 404);
            }
            break;

        case 'withdrawal':
            require_once __DIR__ . '/../controllers/WithdrawalController.php';
            $withdrawalController = new WithdrawalController();
            
            if ($action === 'add' && $method === 'POST') {
                $withdrawalController->addWithdrawal();
            } elseif ($action === 'get' && $method === 'GET' && isset($segments[2])) {
                $withdrawalController->getWithdrawal();
            } elseif ($action === 'list' && $method === 'GET') {
                $withdrawalController->getAllWithdrawals();
            } elseif ($action === 'history' && $method === 'GET') {
                $withdrawalController->getWithdrawalHistory();
            } elseif ($action === 'update' && $method === 'PUT') {
                $withdrawalController->updateWithdrawal();
            } elseif ($action === 'delete' && $method === 'DELETE') {
                $withdrawalController->deleteWithdrawal();
            } else {
                sendError("Invalid withdrawal endpoint", 404);
            }
            break;

        case 'report':
            require_once __DIR__ . '/../controllers/ReportController.php';
            $reportController = new ReportController();
            
            if ($action === 'date-wise' && $method === 'GET') {
                $reportController->getDateWiseReport();
            } elseif ($action === 'monthly' && $method === 'GET') {
                $reportController->getMonthlyReport();
            } elseif ($action === 'all-monthly' && $method === 'GET') {
                $reportController->getAllMonthlyReports();
            } else {
                sendError("Invalid report endpoint", 404);
            }
            break;

        case 'backup':
            require_once __DIR__ . '/../controllers/BackupController.php';
            $backupController = new BackupController();
            
            if ($action === 'create' && $method === 'GET') {
                $backupController->createBackup();
            } elseif ($action === 'restore' && $method === 'POST') {
                $backupController->restoreBackup();
            } else {
                sendError("Invalid backup endpoint", 404);
            }
            break;

        default:
            sendError("Invalid API endpoint", 404);
            break;
    }
} catch(Exception $e) {
    error_log("API Router Error: " . $e->getMessage());
    sendError("Server error occurred", 500);
}

