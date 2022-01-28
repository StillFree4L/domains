<?php

// Указываем пространство имен
namespace Facebook\WebDriver;

// Указываем какие классы будут использоватся
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Firefox\FirefoxOptions;
use Facebook\WebDriver\Interactions\WebDriverActions;
use Facebook\WebDriver\Exception\WebDriverException;
use Facebook\WebDriver\Local\LocalWebDriver;

require_once dirname(__FILE__)."/facebook_webdriver/WebDriverNavigationInterface.php";
require_once dirname(__FILE__)."/facebook_webdriver/WebDriverSearchContext.php";
require_once dirname(__FILE__)."/facebook_webdriver/WebDriverElement.php";
require_once dirname(__FILE__)."/facebook_webdriver/WebDriver.php";
require_once dirname(__FILE__)."/facebook_webdriver/WebDriverBy.php";
require_once dirname(__FILE__)."/facebook_webdriver/WebDriverHasInputDevices.php";
require_once dirname(__FILE__)."/facebook_webdriver/JavaScriptExecutor.php";
require_once dirname(__FILE__)."/facebook_webdriver/WebDriverCapabilities.php";
require_once dirname(__FILE__)."/facebook_webdriver/WebDriverPlatform.php";
require_once dirname(__FILE__)."/facebook_webdriver/WebDriverCommandExecutor.php";
require_once dirname(__FILE__)."/facebook_webdriver/WebDriverOptions.php";
require_once dirname(__FILE__)."/facebook_webdriver/WebDriverWindow.php";
require_once dirname(__FILE__)."/facebook_webdriver/WebDriverPoint.php";
require_once dirname(__FILE__)."/facebook_webdriver/WebDriverWait.php";
require_once dirname(__FILE__)."/facebook_webdriver/WebDriverDimension.php";
require_once dirname(__FILE__)."/facebook_webdriver/WebDriverKeys.php";
require_once dirname(__FILE__)."/facebook_webdriver/WebDriverKeyboard.php";
require_once dirname(__FILE__)."/facebook_webdriver/WebDriverMouse.php";
require_once dirname(__FILE__)."/facebook_webdriver/WebDriverTargetLocator.php";
require_once dirname(__FILE__)."/facebook_webdriver/WebDriverAlert.php";
require_once dirname(__FILE__)."/facebook_webdriver/WebDriverNavigation.php";
require_once dirname(__FILE__)."/facebook_webdriver/WebDriverTimeouts.php";

require_once dirname(__FILE__)."/facebook_webdriver/Remote/WebDriverCommand.php";
require_once dirname(__FILE__)."/facebook_webdriver/Remote/WebDriverCapabilityType.php";
require_once dirname(__FILE__)."/facebook_webdriver/Remote/WebDriverBrowserType.php";
require_once dirname(__FILE__)."/facebook_webdriver/Remote/HttpCommandExecutor.php";
require_once dirname(__FILE__)."/facebook_webdriver/Remote/DriverCommand.php";
require_once dirname(__FILE__)."/facebook_webdriver/Remote/RemoteWebDriver.php";

require_once dirname(__FILE__)."/facebook_webdriver/Local/LocalWebDriver.php";

require_once dirname(__FILE__)."/facebook_webdriver/Internal/WebDriverLocatable.php";

require_once dirname(__FILE__)."/facebook_webdriver/Remote/DesiredCapabilities.php";
require_once dirname(__FILE__)."/facebook_webdriver/Remote/JsonWireCompat.php";
require_once dirname(__FILE__)."/facebook_webdriver/Remote/CustomWebDriverCommand.php";
require_once dirname(__FILE__)."/facebook_webdriver/Remote/RemoteWebElement.php";
require_once dirname(__FILE__)."/facebook_webdriver/Remote/ExecuteMethod.php";
require_once dirname(__FILE__)."/facebook_webdriver/Remote/FileDetector.php";
require_once dirname(__FILE__)."/facebook_webdriver/Remote/UselessFileDetector.php";
require_once dirname(__FILE__)."/facebook_webdriver/Remote/RemoteExecuteMethod.php";
require_once dirname(__FILE__)."/facebook_webdriver/Remote/RemoteWebDriver.php";
require_once dirname(__FILE__)."/facebook_webdriver/Remote/WebDriverResponse.php";
require_once dirname(__FILE__)."/facebook_webdriver/Remote/Service/DriverService.php";
require_once dirname(__FILE__)."/facebook_webdriver/Remote/Service/DriverCommandExecutor.php";
require_once dirname(__FILE__)."/facebook_webdriver/Remote/RemoteKeyboard.php";
require_once dirname(__FILE__)."/facebook_webdriver/Remote/RemoteMouse.php";
require_once dirname(__FILE__)."/facebook_webdriver/Remote/RemoteTargetLocator.php";

require_once dirname(__FILE__)."/facebook_webdriver/Local/LocalWebDriver.php";

require_once dirname(__FILE__)."/facebook_webdriver/Firefox/FirefoxPreferences.php";
require_once dirname(__FILE__)."/facebook_webdriver/Firefox/FirefoxProfile.php";
require_once dirname(__FILE__)."/facebook_webdriver/Firefox/FirefoxDriver.php";
require_once dirname(__FILE__)."/facebook_webdriver/Firefox/FirefoxOptions.php";

require_once dirname(__FILE__)."/facebook_webdriver/Chrome/ChromeOptions.php";
require_once dirname(__FILE__)."/facebook_webdriver/Chrome/ChromeDriver.php";
require_once dirname(__FILE__)."/facebook_webdriver/Chrome/ChromeDriverService.php";

require_once dirname(__FILE__)."/facebook_webdriver/Exception/WebDriverException.php";
require_once dirname(__FILE__)."/facebook_webdriver/Exception/ElementClickInterceptedException.php";
require_once dirname(__FILE__)."/facebook_webdriver/Exception/NoSuchAlertException.php";
require_once dirname(__FILE__)."/facebook_webdriver/Exception/ElementNotInteractableException.php";
require_once dirname(__FILE__)."/facebook_webdriver/Exception/UnknownErrorException.php";
require_once dirname(__FILE__)."/facebook_webdriver/Exception/JavascriptErrorException.php";
require_once dirname(__FILE__)."/facebook_webdriver/Exception/UnexpectedJavascriptException.php";
require_once dirname(__FILE__)."/facebook_webdriver/Exception/UnknownServerException.php";
require_once dirname(__FILE__)."/facebook_webdriver/Exception/WebDriverCurlException.php";
require_once dirname(__FILE__)."/facebook_webdriver/Exception/SessionNotCreatedException.php";
require_once dirname(__FILE__)."/facebook_webdriver/Exception/InvalidSelectorException.php";
require_once dirname(__FILE__)."/facebook_webdriver/Exception/ElementNotVisibleException.php";
require_once dirname(__FILE__)."/facebook_webdriver/Exception/UnrecognizedExceptionException.php";
require_once dirname(__FILE__)."/facebook_webdriver/Exception/NoSuchElementException.php";
require_once dirname(__FILE__)."/facebook_webdriver/Exception/StaleElementReferenceException.php";
require_once dirname(__FILE__)."/facebook_webdriver/Exception/ExpectedException.php";
require_once dirname(__FILE__)."/facebook_webdriver/Exception/ElementNotSelectableException.php";
require_once dirname(__FILE__)."/facebook_webdriver/Exception/NoAlertOpenException.php";
require_once dirname(__FILE__)."/facebook_webdriver/Exception/TimeOutException.php";
require_once dirname(__FILE__)."/facebook_webdriver/Exception/UnexpectedAlertOpenException.php";
require_once dirname(__FILE__)."/facebook_webdriver/Exception/NoSuchWindowException.php";
require_once dirname(__FILE__)."/facebook_webdriver/Exception/InvalidArgumentException.php";

require_once dirname(__FILE__)."/facebook_webdriver/WebDriverAction.php";
require_once dirname(__FILE__)."/facebook_webdriver/Interactions/WebDriverActions.php";
require_once dirname(__FILE__)."/facebook_webdriver/Interactions/WebDriverCompositeAction.php";
require_once dirname(__FILE__)."/facebook_webdriver/Interactions/WebDriverTouchActions.php";

require_once dirname(__FILE__)."/facebook_webdriver/Interactions/Internal/WebDriverMouseAction.php";
require_once dirname(__FILE__)."/facebook_webdriver/Interactions/Internal/WebDriverMouseMoveAction.php";
require_once dirname(__FILE__)."/facebook_webdriver/Interactions/Internal/WebDriverMoveToOffsetAction.php";
require_once dirname(__FILE__)."/facebook_webdriver/Interactions/Internal/WebDriverCoordinates.php";

?>
