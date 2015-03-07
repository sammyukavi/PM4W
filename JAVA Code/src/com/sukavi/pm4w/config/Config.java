package com.sukavi.pm4w.config;

public class Config {

    public static String APP_VERSION;

    public static final String API_URL = "http://pm4w.uct.ac.za/api/";//with forward slash

    //public static final String API_URL = "http://192.168.57.1/api/";//with forward slash 

    // Google project id
    public static final String GOOGLE_PROJECT_ID = "901753417814"; 

    public static final String STORAGE_DRIECTORY_NAME = "pm4w";


    public static final String DISPLAY_MESSAGE_ACTION =  "com.sukavi.pm4w.DISPLAY_MESSAGE";

    public static final String EXTRA_MESSAGE = "message";

    //phone number formats	
    public static final String PNUMBER_FORMATS[] = {"############","##########"};


    //Server Status
    public static final int SERVER_OFFLINE=0;
    public static final int SERVER_ONLINE=1;
    public static final int SERVER_UPGRADE=2;

    //Request Status
    public static final int REQUEST_FAILED=0;
    public static final int REQUEST_SUCCESSFUL=1;
    public static final int REQUEST_PENDING=2;


    //server messages

    public static String ERROR="ERROR";
    public static String PLEASE_WAIT = "Please wait";
    public static String CANCEL = "Cancel";
    public static String OK = "Ok";
    public static String YES = "Yes";
    public static String NO = "No";
    public static String CHECKING_CONNECTIVITY = "Checking connectivity";
    public static String SUCCESS = "Success";
    public static String INFO = "INFO";
    public static String UPDATING = "Updating";
    public static String DELETE = "Delete";
    public static String REPORT = "Report";
    public static String SELECT_ACTION = "Select action";
    public static String FINISH = "Finish";
    public static String ANOTHER_SALE = "Another Sale";
    public static String UPDATE="Update";


    public static String USERNAME_REQUIRED = "Please enter your username, email address or phone number";
    public static String PASSWORD_REQUIRED = "Password cannot be empty.";
    public static String FIRST_NAME_REQUIRED_ERROR="First name is required.";
    public static String LAST_NAME_REQUIRED_ERROR="Last name is required.";
    public static String INVALID_PHONE_NUMBER_FORMAT = "Invalid phone number format. Please use the format shown.";
    public static String NAME_REQUIRED_ERROR = "Your name is required";
    public static String MESSAGE_REQUIRED_ERROR = "You cannot send a blank sms";

    public static String AMOUNT_REQUIRED_ERROR = "Please enter an amount for sale.";
    public static String WATERSOURCE_REQUIRED_ERROR = "Please choose a water source.";
    public static String EXPENDITURETYPE_REQUIRED_ERROR = "Please choose an expenditure type.";
    public static String EXPENDITURE_COST_REQUIRED_ERROR = "The cost of the expenditure is required";
    public static String EXPENDITURE_BENEFACTOR_REQUIRED_ERROR = "The person being paid is required";
    public static String EXPENDITURE_DESCRIPTION_REQUIRED_ERROR = "A description of the expenditure is required";
    public static String SENDING_DATA = "Sending data";
    public static String RESUMING_SESSION = "Resuming session";
    public static String LOGGING_IN = "Logging in";
    public static String REGISTERING_DEVICE = "Registering device";
    public static String UNDEFINED_REQUEST = "Undefined request";

    public static String DELETE_WATER_USER_MSG = "Are you sure you want to delete this user? This process is not reversible.";
    public static String REPORT_WATER_USER_MSG = "You are advised to send a message to a defaulter before you report. Are you sure you want to report this user?";
    public static String WATER_USER_NO_PHONE_NUMBER = "That water user has no phone number therefore it's not possible to send an sms";


    public static String NO_INTERNET_TITLE = "Failed to connect";
    public static String NO_INTERNET_MSG="You need internet to continue with this service.";

    public static String OFFLINE_TITLE = "Server is offline";
    public static String OFFLINE_MSG = "The server is offline. Please try again later.";

    public static String UPGRADE_TITLE = "Server is Upgrading";
    public static String UPGRADE_MSG="The server is undergoing an upgrade. Please try again later.";
    public static String ERROR_READING_FROM_SERVER= "An error occured reading data from the server. Please try again later. If this continues please consult your system administrator.";
    public static String SERVER_NOT_ACCESSIBLE= "The server is not available. Please try again later. If this continues please consult your system administrator.";

    public static String NO_CUSTOMERS_ON_MONTHLY_BILLING = "You do not have any registered water users";
    public static String NO_DEFAULTERS = "No defaulters to show";
    public static String NO_TRANSACTIONS = "No transactions to show";


    public static String CANCEL_SUBMITTION_MESSAGE = "Are you sure you want to cancel this submittion?";
    public static String GROUP_DISABLED ="You cannot be logged in because your user group has been deactivated. Please consult your administrator for further advice.";
    public static String ACTION_DISABLED ="You do not have the required rights to perform this action. If you feel this is an error, please consult your administrator for further advice.";

    public static final String UPDATE_AVAILABLE = "An update of PM4W app is available. It is mandatory that you install the update. Please select install then sletect open on the next screen to complete the process.";
    public static final String UPDATE_AVAILABLE_INSUFFICIENT_SPACE = "An update of this app is available for download but you require free space in your memory card to update";
    public static final String UPDATE_AVAILABLE_NO_MEMORY_CARD = "An update of this app is available for download but you require a memory card to update this app";
    public static final String UPDATE_ERROR = "Update error! ";
    public static final String CANCEL_DISABLED_DOWNLOADING_UPDATE = "Downloading an update. This wont take long. Please wait. ";
    public static final String NO_UPDATE_AVAILABLE = "No update is available. You have the latest version installed.";
    public static final String DOWNLOADING_UPDATE= "Downloading update.";
    public static final String CONFIRM_CANCEL_DOWNLOAD= "Are you sure you want to cancel this download? Resume is not supported and all progress will be discarded. You will have to start the download again.";
    public static final String CANCEL_DOWNLOAD="Cancel Download";
    public static final String FINISH_DOWNLOADING="Download";

}
