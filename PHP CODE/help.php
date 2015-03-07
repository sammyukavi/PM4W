<?php require 'config.php'; ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Help | <?php echo SYSTEM_NAME; ?></title>     
        <meta name="robots" content="<?php echo str_replace('-', ', ', getArrayVal($SYSTEM_CONFIG, 'robots')); ?>"> 
        <meta name="description" content="<?php echo str_replace('-', ', ', getArrayVal($SYSTEM_CONFIG, 'site_desc')); ?>">
        <meta name="keywords" content="<?php echo str_replace('-', ', ', getArrayVal($SYSTEM_CONFIG, 'site_keywords')); ?>">
        <meta name="author" content="Sammy N Ukavi Jr">
        <!-- Sets initial viewport load and disables zooming  -->
        <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">       
        <meta name="google-site-verification" content="m_GJfXZa9_FiKQLJ5Pa1iyHDIHeiWk39XNxifn_dQZE" />

        <link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="60x60" href="/apple-touch-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/apple-touch-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon-180x180.png">
        <meta name="apple-mobile-web-app-title" content="<?php echo SYSTEM_NAME; ?>">
        <link rel="icon" type="image/png" href="/favicon-192x192.png" sizes="192x192">
        <link rel="icon" type="image/png" href="/favicon-160x160.png" sizes="160x160">
        <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96">
        <link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">
        <link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="msapplication-TileImage" content="/mstile-144x144.png">
        <meta name="application-name" content="<?php echo SYSTEM_NAME; ?>">

        <link rel="bookmark" href="/favicon-16x16.png"/>  

        <!-- site css -->
        <link rel="stylesheet" href="/assets/css/front.css">
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,800,700,400italic,600italic,700italic,800italic,300italic" rel="stylesheet" type="text/css">
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
        <!--[if lt IE 9]>
          <script src="/assets/js/html5shiv.js"></script>
          <script src="/assets/js/respond.min.js"></script>
        <![endif]-->
        <script type="text/javascript" src="/assets/js/site.min.js"></script>
    </head>
    <body style="background-color: #f1f2f6;">
        <div class="docs-header">
            <!--nav-->
            <nav class="navbar navbar-default navbar-custom" role="navigation">
                <div class="container">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="/">
                            <img src="/apple-touch-icon-60x60.png" height="40">
                            <?php echo SYSTEM_NAME; ?>
                        </a>
                    </div>
                    <div class="collapse navbar-collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <li><a class="nav-link" href="/downloads/">Download</a></li>
                            <li><a class="nav-link current" href="/help.php">Help</a></li>
                            <li><a class="nav-link" href="/manage/" target="_blank">Login</a></li>                          
                        </ul>
                    </div>
                </div>
            </nav>
            <!--header-->
            <div class="topic">
                <div class="container">
                    <div class="col-md-8">
                        <h3>PM4W User GUIDELINES</h3>
                        <h4>(Pay Me For Water – an automated platform to help rural communities manage water user fees)</h4>
                        <h4>February 2015</h4>
                    </div>                    
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">                
                    <h1>Contents</h1>
                    <p><a href="#__RefHeading__1_262122713">Contents 2</a></p>
                    <p><a href="#__RefHeading__3_262122713">General Information	3</a></p>
                    <p><a href="#__RefHeading__5_262122713">Trouble Shooting Guide 	3</a></p>
                    <p><a href="#__RefHeading__7_262122713">Dos and Donts 4</a></p>
                    <p><a href="#__RefHeading__9_262122713">Contacts for Help 4</a></p>
                    <p><a href="#__RefHeading__11_262122713">GUIDELINES FOR USERS 5</a></p>                    
                    <h1><A NAME="__RefHeading__3_262122713"></a>General
                        Information</h1>
                    <p>The
                        PM4W (Pay Me For Water) system uses mobile phones to facilitate the
                        monitoring and management of water user fees in rural communities in
                        order to improve collection, usage and accountability of communally
                        raised water user fees. It is hoped that improving the financial
                        management of water user fees will encourage communities to pay for
                        the water services and lead to improved functionality and access to
                        water services for the communities served.</p>
                    <p>
                        The stakeholders using the PM4W system and their roles are outlined
                        below:</p>
                    <OL>
                        <LI><p>
                                <B>Caretakers/scheme attendants:</B> Register water users
                                (caretakers) and provide information on daily transactions or amount
                                of user fees collected monthly. Where applicable, they use
                                information on non-paid users in order to follow them up and collect
                                the unpaid dues. In addition, they provide information on operation
                                and maintenance activities conducted and paid for from the community
                                finances.</p>
                        <LI><p>
                                <B>Water user committee (treasurer): </B>provide information on
                                community fees collected, contributions to the sub-county water
                                boards and any maintenance activities carried out on a water source.
                            </p>
                        <LI><p>
                                <B>Sub-county water board (treasurer)</B>:<B>  </B>They provide
                                information on contributions from communities and overall financial
                                status for the community. They too provide information on major
                                operation and maintenance activities financed by the board.</p>
                        <LI><p>
                                <B>District Water Officer (DWO): </B>The officer accesses reports on
                                the operation and maintenance activities done as well as the
                                financial status of the specific communities that are using a
                                particular water source. 
                            </p>
                        <LI><p><B>Community
                                    members: </B>They receive notifications of summary transactions
                                (that is, monthly collections and expenditures).  
                            </p>
                    </OL>
                    <h1>Trouble Shooting Guide	</h1>
                    <TABLE WIDTH=650 CELLPADDING=7 CELLSPACING=0>
                        <COL WIDTH=143>
                        <COL WIDTH=184>
                        <COL WIDTH=278>
                        <TR VALIGN=TOP>
                            <TD WIDTH=143 BGCOLOR="#9e3a38" STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: none; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0in">
                                <p><FONT COLOR="#ffffff"><FONT SIZE=2 STYLE="font-size: 11pt"><B>Problem</B></FONT></FONT></p>
                            </TD>
                            <TD WIDTH=184 BGCOLOR="#9e3a38" STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: none; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0in">
                                <p><FONT COLOR="#ffffff"><FONT SIZE=2 STYLE="font-size: 11pt"><B>Possible
                                        Causes</B></FONT></FONT></p>
                            </TD>
                            <TD WIDTH=278 BGCOLOR="#9e3a38" STYLE="border: 1px solid #000000; padding: 0in 0.08in">
                                <p><FONT COLOR="#ffffff"><FONT SIZE=2 STYLE="font-size: 11pt"><B>Actions</B></FONT></FONT></p>
                            </TD>
                        </TR>
                        <TR VALIGN=TOP>
                            <TD WIDTH=143 BGCOLOR="#ffffff" STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: none; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0in">
                                <p><FONT SIZE=2 STYLE="font-size: 11pt"><B>Unable
                                        to upload / download data</B></FONT></p>
                            </TD>
                            <TD WIDTH=184 BGCOLOR="#ffffff" STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: none; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0in">
                                <p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">Wrong
                                    Internet connection settings , expired data bundle</FONT></FONT></p>
                            </TD>
                            <TD WIDTH=278 BGCOLOR="#ffffff" STYLE="border: 1px solid #000000; padding: 0in 0.08in">
                                <p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">Contact
                                    DWO or Assist. DWO for help.</FONT></FONT></p>
                            </TD>
                        </TR>
                        <TR VALIGN=TOP>
                            <TD WIDTH=143 BGCOLOR="#ffffff" STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: none; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0in">
                                <p><FONT SIZE=2 STYLE="font-size: 11pt"><B>Unable
                                        to establish connection</B></FONT></p>
                            </TD>
                            <TD WIDTH=184 BGCOLOR="#ffffff" STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: none; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0in">
                                <p><FONT SIZE=2 STYLE="font-size: 11pt">Poor
                                    network connection</FONT></p>
                            </TD>
                            <TD WIDTH=278 BGCOLOR="#ffffff" STYLE="border: 1px solid #000000; padding: 0in 0.08in">
                                <p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">Go
                                    to a place with good network connection. Restart the phone if
                                    problem persists</FONT></FONT></p>
                            </TD>
                        </TR>
                        <TR VALIGN=TOP>
                            <TD WIDTH=143 BGCOLOR="#ffffff" STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: none; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0in">
                                <p><FONT SIZE=2 STYLE="font-size: 11pt"><B>Error:
                                        Access denied</B></FONT></p>
                            </TD>
                            <TD WIDTH=184 BGCOLOR="#ffffff" STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: none; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0in">
                                <p><FONT SIZE=2 STYLE="font-size: 11pt">Wrong user
                                    name or password</FONT></p>
                            </TD>
                            <TD WIDTH=278 BGCOLOR="#ffffff" STYLE="border: 1px solid #000000; padding: 0in 0.08in">
                                <p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">Enter
                                    correct user name and password, Contact DWO for help.</FONT></FONT></p>
                            </TD>
                        </TR>
                        <TR VALIGN=TOP>
                            <TD WIDTH=143 BGCOLOR="#ffffff" STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: none; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0in">
                                <p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><B>PM4W
                                        application unavailable</B></FONT></FONT></p>
                            </TD>
                            <TD WIDTH=184 BGCOLOR="#ffffff" STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: none; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0in">
                                <p><FONT SIZE=2 STYLE="font-size: 11pt">Application
                                    has been deleted</FONT></p>
                            </TD>
                            <TD WIDTH=278 BGCOLOR="#ffffff" STYLE="border: 1px solid #000000; padding: 0in 0.08in">
                                <p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">Please
                                    contact DWO  for guidelines on how to restore application</FONT></FONT></p>
                            </TD>
                        </TR>
                    </TABLE>
                    <h1>Dos and Donts</h1>
                    <UL>
                        <LI><p STYLE="margin-top: 0.14in; margin-bottom: 0.14in">Ensure the
                                phone is adequately charged before embarking on data collection to
                                avoid losing data</p>
                        <LI><p STYLE="margin-top: 0.14in; margin-bottom: 0.14in">Ensure that
                                all  fields are filled before saving the forms</p>
                        <LI><p STYLE="margin-top: 0.14in; margin-bottom: 0.14in">Scheme
                                attendants should always upload data at the end of the day.  
                            </p>
                        <LI><p STYLE="margin-top: 0.14in; margin-bottom: 0.14in">Care takers
                                and treasurers should upload data on all transactions as soon as
                                they take place.</p>
                        <LI><p STYLE="margin-top: 0.14in; margin-bottom: 0.14in">Make sure
                                you have all the required information before filling any form as
                                saving incomplete forms is not possible</p>
                    </UL>
                    <UL>
                        <LI><p STYLE="margin-top: 0.14in; margin-bottom: 0.14in">Do not swap
                                or change SIM cards in the phone. This may change the Internet
                                settings and cause errors.</p>
                        <LI><p STYLE="margin-top: 0.14in; margin-bottom: 0.14in">Keep the
                                phone in a place that is safe and secure. Avoid using it as a
                                personal phone as this will shorten its life span</p>
                    </UL>
                    <H2><A NAME="__RefHeading__9_262122713"></a>Contacts
                        for Help</H2>
                    <p STYLE="margin-top: 0.14in; margin-bottom: 0.14in">The
                        primary contact is the <B>DWO</B>:  Mr. Pius Mugabi: 0782 451886</p>
                    <p STYLE="margin-top: 0.14in; margin-bottom: 0.14in">
                        or <B>Assist. DWO</B>: Mr Nathan Mugabe: 0772 355899; 
                    </p>
                    <p STYLE="margin-top: 0.14in; margin-bottom: 0.14in"><BR><BR>
                    </p>
                    <h1 STYLE="page-break-before: always"><A NAME="__RefHeading__11_262122713"></a>
                        GUIDELINES FOR USERS</h1>
                    <p STYLE="margin-top: 0.14in; margin-bottom: 0.14in"><BR><BR>
                    </p>
                    <TABLE WIDTH=566 CELLPADDING=7 CELLSPACING=0>
                        <COL WIDTH=148>
                        <COL WIDTH=180>
                        <COL WIDTH=194>
                        <TR>
                            <TD COLSPAN=3 WIDTH=550 VALIGN=TOP BGCOLOR="#9e3a38" STYLE="border: 1px solid #000000; padding: 0in 0.08in">
                                <p ALIGN=CENTER><FONT COLOR="#ffffff"><FONT SIZE=4><B>Important
                                        Buttons</B></FONT></FONT></p>
                            </TD>
                        </TR>
                        <TR VALIGN=TOP>
                            <TD WIDTH=148 BGCOLOR="#ffffff" STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: none; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0in">
                                <p ALIGN=CENTER STYLE="margin-top: 0.14in; margin-bottom: 0.17in">
                                    <IMG SRC="/assets/img/i_6e18d397cb89b0e0_html_3fba7338.jpg" NAME="graphics1" ALIGN=BOTTOM WIDTH=58 HEIGHT=35 BORDER=0></p>
                                <p STYLE="margin-top: 0.14in"><FONT COLOR="#000000">‘</FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><B>Back’
                                        button: </B></FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">Use
                                    this button to return to the previous screen.</FONT></FONT></p>
                            </TD>
                            <TD WIDTH=180 BGCOLOR="#ffffff" STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: none; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0in">
                                <p ALIGN=CENTER STYLE="margin-top: 0.14in; margin-bottom: 0.17in">
                                    <IMG SRC="/assets/img/i_6e18d397cb89b0e0_html_38c8c7a1.jpg" NAME="graphics2" ALIGN=BOTTOM WIDTH=57 HEIGHT=40 BORDER=0></p>
                                <p STYLE="margin-top: 0.14in"><FONT COLOR="#000000">‘</FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><B>Home’
                                        button: </B></FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">Use
                                    this to return to the home screen or to exit the PM4W system.</FONT></FONT></p>
                            </TD>
                            <TD WIDTH=194 BGCOLOR="#ffffff" STYLE="border: 1px solid #000000; padding: 0in 0.08in">
                                <p ALIGN=CENTER STYLE="margin-top: 0.14in; margin-bottom: 0.17in">
                                    <IMG SRC="/assets/img/i_6e18d397cb89b0e0_html_12706884.jpg" NAME="graphics3" ALIGN=BOTTOM WIDTH=69 HEIGHT=38 BORDER=0></p>
                                <p STYLE="margin-top: 0.14in"><FONT COLOR="#000000">‘</FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><B>Menu’
                                        button: </B></FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">Use
                                    this to log out (</FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><B>‘Log
                                        out’</B></FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">)or
                                    to find out about the PM4W system (</FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><B>About</B></FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">)</FONT></FONT></p>
                            </TD>
                        </TR>
                        <TR>
                            <TD COLSPAN=3 WIDTH=550 VALIGN=TOP BGCOLOR="#833c0b" STYLE="border: 1px solid #000000; padding: 0in 0.08in">
                                <p ALIGN=CENTER><FONT COLOR="#ffffff"><FONT SIZE=4><B>How
                                        to Start</B></FONT></FONT></p>
                            </TD>
                        </TR>
                        <TR>
                            <TD COLSPAN=3 WIDTH=550 HEIGHT=254 VALIGN=TOP BGCOLOR="#ffffff" STYLE="border: 1px solid #000000; padding: 0in 0.08in">
                                <p STYLE="margin-bottom: 0in"><BR>
                                </p>
                                <p STYLE="margin-bottom: 0in"><IMG SRC="/assets/img/i_6e18d397cb89b0e0_html_m546fc88e.jpg" NAME="graphics4" ALIGN=BOTTOM WIDTH=188 HEIGHT=252 BORDER=0><SPAN ID="Frame1" DIR="LTR" STYLE="float: left; width: 3in; height: 1.98in; border: 1px solid #000000; padding: 0.05in 0.1in; background: #ffffff">
                                        <UL>
                                            <LI><p STYLE="margin-top: 0.14in; margin-bottom: 0.14in">
                                                    Find the <B>PM4W</B> application on your <B>‘home’</B>
                                                    screen or in the list of applications on the phone.</p>
                                            <LI><p STYLE="margin-top: 0.14in">Select the
                                                    application.</p>
                                        </UL>
                                    </SPAN>
                                </p>
                                <p STYLE="margin-bottom: 0in"><BR>
                                </p>
                                <p><BR>
                                </p>
                            </TD>
                        </TR>
                        <TR>
                            <TD COLSPAN=3 WIDTH=550 VALIGN=TOP BGCOLOR="#ffffff" STYLE="border: 1px solid #000000; padding: 0in 0.08in">
                                <p STYLE="margin-bottom: 0in"><IMG SRC="/assets/img/i_6e18d397cb89b0e0_html_m49cf84e1.png" NAME="graphics5" ALIGN=BOTTOM WIDTH=180 HEIGHT=270 BORDER=0><SPAN ID="Frame2" DIR="LTR" STYLE="float: left; width: 2.49in; border: 1px solid #000000; padding: 0.05in 0.1in; background: #ffffff">
                                        <UL>
                                            <LI><p STYLE="margin-bottom: 0in">Enter your
                                                    ‘username’ (given to you) e.g ‘kicct’</p>
                                            <LI><p STYLE="margin-bottom: 0in">Enter your
                                                    password</p>
                                            <LI><p>Select ‘Login’</p>
                                        </UL>
                                    </SPAN>
                                </p>
                                <p><BR>
                                </p>
                            </TD>
                        </TR>
                    </TABLE>
                    <p STYLE="margin-top: 0.14in; margin-bottom: 0.14in">
                        <BR><BR>
                    </p>
                    <p STYLE="margin-top: 0.14in; margin-bottom: 0.14in; page-break-before: always">
                    <TABLE DIR="LTR" ALIGN=RIGHT WIDTH=965 HSPACE=6 CELLPADDING=7 CELLSPACING=0>
                        <COL WIDTH=229>
                        <COL WIDTH=246>
                        <COL WIDTH=216>
                        <COL WIDTH=215>
                        <TR>
                            <TD COLSPAN=4 WIDTH=949 HEIGHT=32 VALIGN=TOP BGCOLOR="#833c0b" STYLE="border: 1px solid #000000; padding: 0in 0.08in">
                                <p ALIGN=CENTER STYLE="margin-top: 0.14in"><FONT COLOR="#ffffff"><FONT SIZE=4><B>Guidelines
                                        for Caretakers/Scheme attendants</B></FONT></FONT></p>
                            </TD>
                        </TR>
                        <TR VALIGN=TOP>
                            <TD WIDTH=229 STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: none; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0in">
                                <p><IMG SRC="/assets/img/i_6e18d397cb89b0e0_html_m642ee8b5.png" NAME="graphics6" ALIGN=BOTTOM WIDTH=220 HEIGHT=330 BORDER=0><SPAN ID="Frame4" DIR="LTR" STYLE="float: left; width: 2.27in; height: 1.03in; border: 1px solid #000000; padding: 0.05in 0.1in; background: #ffffff">
                                        <UL>
                                            <LI><p>The dash board of the caretaker/scheme
                                                    attendant</p>
                                        </UL>
                                    </SPAN>
                                </p>
                                <p ALIGN=JUSTIFY><BR>
                                </p>
                            </TD>
                            <TD WIDTH=246 STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: none; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0in">
                                <p STYLE="margin-top: 0.14in; margin-bottom: 0.14in">
                                    <IMG SRC="/assets/img/i_6e18d397cb89b0e0_html_21ef3cc8.png" NAME="graphics7" ALIGN=BOTTOM WIDTH=201 HEIGHT=301 BORDER=0><SPAN ID="Frame5" DIR="LTR" STYLE="float: left; width: 2.33in; height: 1.36in; border: 1px solid #000000; padding: 0.05in 0.1in; background: #ffffff">
                                        <p STYLE="margin-bottom: 0in"> Select ‘Water
                                            users’ on dashboard:</p>
                                        <UL>
                                            <LI><p STYLE="margin-bottom: 0in">To register
                                                    new users</p>
                                            <LI><p>To view the list of registered water
                                                    source users</p>
                                        </UL>
                                    </SPAN>
                                </p>
                                <p ALIGN=JUSTIFY><BR>
                                </p>
                            </TD>
                            <TD WIDTH=216 STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: none; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0in">
                                <p STYLE="margin-top: 0.14in"><IMG SRC="/assets/img/i_6e18d397cb89b0e0_html_m7fce0696.png" NAME="graphics8" ALIGN=BOTTOM WIDTH=208 HEIGHT=311 BORDER=0><SPAN ID="Frame6" DIR="LTR" STYLE="float: left; width: 2.13in; height: 1.5in; border: 1px solid #000000; padding: 0.05in 0.1in; background: #ffffff">
                                        <UL>
                                            <LI><p STYLE="margin-bottom: 0in">To register a
                                                    new user, select <B>‘add water users’</B></p>
                                            <LI><p STYLE="margin-bottom: 0in">Fill the form.</p>
                                            <LI><p>Select <B>‘Add User’</B> to complete
                                                    the registration</p>
                                        </UL>
                                    </SPAN>
                                </p>
                            </TD>
                            <TD WIDTH=215 STYLE="border: 1px solid #000000; padding: 0in 0.08in">
                                <p STYLE="margin-top: 0.14in"><IMG SRC="/assets/img/i_6e18d397cb89b0e0_html_m6380d1b7.png" NAME="graphics9" ALIGN=BOTTOM WIDTH=194 HEIGHT=291 BORDER=0><SPAN ID="Frame7" DIR="LTR" STYLE="float: left; width: 2.19in; height: 1.73in; border: 1px solid #000000; padding: 0.05in 0.1in; background: #ffffff">
                                        <UL>
                                            <LI><p STYLE="margin-bottom: 0in">To view a list
                                                    of registered users, select <B>‘List water users’</B> 
                                                </p>
                                            <LI><p>To view user details, tap on the name of
                                                    the user and then you can edit or delete the user.</p>
                                        </UL>
                                    </SPAN>
                                </p>
                            </TD>
                        </TR>
                        <TR VALIGN=TOP>
                            <TD WIDTH=229 STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: none; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0in">
                                <p STYLE="margin-top: 0.14in; margin-bottom: 0.14in">
                                    <IMG SRC="/assets/img/i_6e18d397cb89b0e0_html_7b81ba15.png" NAME="graphics10" ALIGN=BOTTOM WIDTH=204 HEIGHT=305 BORDER=0><SPAN ID="Frame8" DIR="LTR" STYLE="float: left; width: 2.34in; height: 1.35in; border: 1px solid #000000; padding: 0.05in 0.1in; background: #ffffff">
                                        <UL>
                                            <LI><p>Select <B>‘sales’ </B>dashboard when
                                                    you have collected money or when you want to see who has not
                                                    paid (to follow up)</p>
                                        </UL>
                                    </SPAN>
                                </p>
                                <p STYLE="margin-top: 0.14in"><BR>
                                </p>
                            </TD>
                            <TD WIDTH=246 STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: none; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0in">
                                <p STYLE="margin-top: 0.14in; margin-bottom: 0.14in">
                                    <IMG SRC="/assets/img/i_6e18d397cb89b0e0_html_2a37b5f.png" NAME="graphics11" ALIGN=BOTTOM WIDTH=201 HEIGHT=302 BORDER=0><SPAN ID="Frame9" DIR="LTR" STYLE="float: left; width: 2.39in; height: 1.55in; border: 1px solid #000000; padding: 0.05in 0.1in; background: #ffffff">
                                        <p STYLE="margin-bottom: 0in"><U><B>KASENDA (TAP)
                                                ONLY</B></U></p>
                                <UL>
                                    <LI><p STYLE="margin-bottom: 0in">Select ‘<B>daily
                                                sale’ </B>to enter amount of daily collections</p>
                                    <LI><p STYLE="margin-bottom: 0in">Enter the
                                            amount of money collected for the day.</p>
                                    <LI><p>Select ‘<B>Add Daily Sale’ </B>to
                                            submit the information</p>
                                </UL>
                                </SPAN>
                                </p>
                                <p STYLE="margin-top: 0.14in; margin-bottom: 0.14in">
                                    <BR><BR>
                                </p>
                                <p STYLE="margin-top: 0.14in"><BR>
                                </p>
                            </TD>
                            <TD WIDTH=216 STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: none; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0in">
                                <p STYLE="margin-top: 0.14in; margin-bottom: 0.14in">
                                    <IMG SRC="/assets/img/i_6e18d397cb89b0e0_html_438adb6d.png" NAME="graphics12" ALIGN=BOTTOM WIDTH=214 HEIGHT=321 BORDER=0><SPAN ID="Frame10" DIR="LTR" STYLE="float: left; width: 2.24in; height: 1.99in; border: 1px solid #000000; padding: 0.05in 0.1in; background: #ffffff">
                                        <p STYLE="margin-bottom: 0in"><U><B>For monthly
                                                collections</B></U></p>
                                <UL>
                                    <LI><p STYLE="margin-bottom: 0in">Select
                                            ‘<B>monthly sales’ </B>to enter amount given by a water
                                            user.</p>
                                    <LI><p STYLE="margin-bottom: 0in">Select the
                                            user from the list of registered users provided &amp; amount
                                            paid.</p>
                                    <LI><p STYLE="margin-bottom: 0in">Select ‘<B>Add
                                                Monthly Sale’ </B>to submit the information</p>
                                </UL>
                                <p STYLE="margin-top: 0.14in"><BR>
                                </p>
                                </SPAN>
                                </p>
                                <p STYLE="margin-top: 0.14in"><BR>
                                </p>
                            </TD>
                            <TD WIDTH=215 STYLE="border: 1px solid #000000; padding: 0in 0.08in">
                                <p STYLE="margin-top: 0.14in; margin-bottom: 0.14in">
                                    <IMG SRC="/assets/img/i_6e18d397cb89b0e0_html_78ef8530.png" NAME="graphics13" ALIGN=BOTTOM WIDTH=190 HEIGHT=285 BORDER=0><SPAN ID="Frame11" DIR="LTR" STYLE="float: left; width: 2.23in; height: 1.25in; border: 1px solid #000000; padding: 0.05in 0.1in; background: #ffffff">
                                        <UL>
                                            <LI><p STYLE="margin-bottom: 0in">Select <B>‘Follow
                                                        Up’</B> on the Sales dashboard to view a list on non-paid
                                                    users.</p>
                                        </UL>
                                        <p STYLE="margin-left: 0.19in"><BR>
                                        </p>
                                    </SPAN>
                                </p>
                                <p STYLE="margin-top: 0.14in; margin-bottom: 0.14in">
                                    <BR><BR>
                                </p>
                                <p STYLE="margin-top: 0.14in"><BR>
                                </p>
                            </TD>
                        </TR>
                        <TR VALIGN=TOP>
                            <TD WIDTH=229 STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: none; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0in">
                                <p STYLE="margin-top: 0.14in; margin-bottom: 0.14in">
                                    <IMG SRC="/assets/img/i_6e18d397cb89b0e0_html_5ec2308e.png" NAME="graphics14" ALIGN=BOTTOM WIDTH=210 HEIGHT=316 BORDER=0><SPAN ID="Frame12" DIR="LTR" STYLE="float: left; width: 2.38in; height: 1.61in; border: 1px solid #000000; padding: 0.05in 0.1in; background: #ffffff">
                                        <UL>
                                            <LI><p STYLE="margin-bottom: 0in">To collect
                                                    money from a non-paid user, select the name of the user.</p>
                                            <LI><p STYLE="margin-bottom: 0in">Fill the form
                                                    with the month which the user is paying for.</p>
                                            <LI><p>Select <B>‘Add monthly sale’</B> to
                                                    submit the data.</p>
                                        </UL>
                                    </SPAN>
                                </p>
                                <p STYLE="margin-top: 0.14in; margin-bottom: 0.14in">
                                    <BR><BR>
                                </p>
                                <p STYLE="margin-top: 0.14in"><BR>
                                </p>
                            </TD>
                            <TD WIDTH=246 STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: none; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0in">
                                <p STYLE="margin-top: 0.14in"><IMG SRC="/assets/img/i_6e18d397cb89b0e0_html_mf42fe67.png" NAME="graphics15" ALIGN=BOTTOM WIDTH=206 HEIGHT=309 BORDER=0><SPAN ID="Frame13" DIR="LTR" STYLE="float: left; width: 2.36in; height: 2.04in; border: 1px solid #000000; padding: 0.05in 0.1in; background: #ffffff">
                                        <UL>
                                            <LI><p STYLE="margin-bottom: 0in">To follow-up
                                                    non-paid users, long press the name of the user you want to
                                                    follow-up until you see a menu as shown.</p>
                                            <LI><p STYLE="margin-bottom: 0in">You can choose
                                                    to send an SMS to the water user or report the water user to the
                                                    WUC treasurer.</p>
                                        </UL>
                                        <p STYLE="margin-top: 0.14in"><BR>
                                        </p>
                                    </SPAN>
                                </p>
                            </TD>
                            <TD WIDTH=216 STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: none; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0in">
                                <p STYLE="margin-top: 0.14in; margin-bottom: 0.14in">
                                    <IMG SRC="/assets/img/i_6e18d397cb89b0e0_html_1c94dcbc.png" NAME="graphics16" ALIGN=BOTTOM WIDTH=215 HEIGHT=322 BORDER=0><SPAN ID="Frame14" DIR="LTR" STYLE="float: left; width: 2.05in; height: 1.94in; border: 1px solid #000000; padding: 0.05in 0.1in; background: #ffffff">
                                        <UL>
                                            <LI><p STYLE="margin-bottom: 0in">To send an SMS
                                                    to remind the water user to pay the monthly user fees.</p>
                                            <LI><p>Just type the SMS (even in the local
                                                    language) and select <B>‘send message’</B></p>
                                        </UL>
                                    </SPAN>
                                </p>
                                <p STYLE="margin-top: 0.14in"><BR>
                                </p>
                            </TD>
                            <TD WIDTH=215 STYLE="border: 1px solid #000000; padding: 0in 0.08in">
                                <p STYLE="margin-top: 0.14in; margin-bottom: 0.14in">
                                    <IMG SRC="/assets/img/i_6e18d397cb89b0e0_html_6294613c.png" NAME="graphics17" ALIGN=BOTTOM WIDTH=201 HEIGHT=301 BORDER=0><SPAN ID="Frame15" DIR="LTR" STYLE="float: left; width: 2.23in; height: 1.45in; border: 1px solid #000000; padding: 0.05in 0.1in; background: #ffffff">
                                        <UL>
                                            <LI><p STYLE="margin-bottom: 0in">To provide
                                                    information on expenses or repairs, select ‘Expenses’ on the
                                                    main dashboard.</p>
                                            <LI><p>Fill the form provided</p>
                                        </UL>
                                    </SPAN>
                                </p>
                                <p STYLE="margin-top: 0.14in"><BR>
                                </p>
                            </TD>
                        </TR>
                        <TR VALIGN=TOP>
                            <TD WIDTH=229 STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: none; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0in">
                                <p STYLE="margin-top: 0.14in; margin-bottom: 0.14in">
                                    <IMG SRC="/assets/img/i_6e18d397cb89b0e0_html_2ebde6bc.png" NAME="graphics18" ALIGN=BOTTOM WIDTH=218 HEIGHT=327 BORDER=0><SPAN ID="Frame16" DIR="LTR" STYLE="float: left; width: 2.36in; height: 1.05in; border: 1px solid #000000; padding: 0.05in 0.1in; background: #ffffff">
                                        <UL>
                                            <LI><p>Complete the ‘expenses’ form and
                                                    select <B>‘save expenditure’</B> to submit the data.</p>
                                        </UL>
                                    </SPAN>
                                </p>
                                <p STYLE="margin-top: 0.14in; margin-bottom: 0.14in">
                                    <BR><BR>
                                </p>
                                <p STYLE="margin-top: 0.14in"><BR>
                                </p>
                            </TD>
                            <TD WIDTH=246 STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: none; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0in">
                                <p STYLE="margin-top: 0.14in; margin-bottom: 0.14in">
                                    <IMG SRC="/assets/img/i_6e18d397cb89b0e0_html_m18f69ab4.png" NAME="graphics19" ALIGN=BOTTOM WIDTH=229 HEIGHT=343 BORDER=0></p>
                                <p LANG="en-US" STYLE="margin-top: 0.14in; margin-bottom: 0.14in">
                                    <SPAN ID="Frame17" DIR="LTR" STYLE="float: left; width: 2.55in; height: 0.98in; border: 1px solid #000000; padding: 0.05in 0.1in; background: #ffffff">
                                        <UL>
                                            <LI><p>Select <B>‘Account’</B> on the main
                                                    dashboard to view the status of the community account</p>
                                        </UL>
                                    </SPAN><BR><BR>
                                </p>
                                <p STYLE="margin-top: 0.14in"><BR>
                                </p>
                            </TD>
                            <TD COLSPAN=2 WIDTH=445 STYLE="border: 1px solid #000000; padding: 0in 0.08in">
                                <p STYLE="margin-top: 0.14in; margin-bottom: 0.14in">
                                    <IMG SRC="/assets/img/i_6e18d397cb89b0e0_html_m30f1cc8e.png" NAME="graphics20" ALIGN=BOTTOM WIDTH=438 HEIGHT=246 BORDER=0></p>
                                <p STYLE="margin-top: 0.14in; margin-bottom: 0.14in">
                                    <BR><BR>
                                </p>
                                <p LANG="en-US" STYLE="margin-top: 0.14in">
                                    <SPAN ID="Frame18" DIR="LTR" STYLE="float: left; width: 3.86in; height: 1.44in; border: 1px solid #000000; padding: 0.05in 0.1in; background: #ffffff">
                                        <UL>
                                            <LI><p STYLE="margin-top: 0.14in">Select <B>‘Mini
                                                        statement’</B> to view a summary of transactions for a
                                                    particular community. 
                                                </p>
                                        </UL>
                                    </SPAN><BR>
                                </p>
                            </TD>
                        </TR>
                        <TR>
                            <TD COLSPAN=4 WIDTH=949 VALIGN=TOP BGCOLOR="#833c0b" STYLE="border: 1px solid #000000; padding: 0in 0.08in">
                                <p ALIGN=CENTER STYLE="margin-top: 0.14in"><FONT COLOR="#ffffff"><FONT SIZE=4><B>Guidelines
                                    </B></FONT></FONT><FONT COLOR="#ffffff"><FONT SIZE=4><B>for
                                        Treasurers (committee and water board)</B></FONT></FONT></p>
                            </TD>
                        </TR>
                        <TR VALIGN=TOP>
                            <TD WIDTH=229 STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: none; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0in">
                                <p STYLE="margin-top: 0.14in"><IMG SRC="/assets/img/i_6e18d397cb89b0e0_html_3c307216.png" NAME="graphics21" ALIGN=BOTTOM WIDTH=206 HEIGHT=309 BORDER=0></p>
                            </TD>
                            <TD WIDTH=246 STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: none; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0in">
                                <p STYLE="margin-top: 0.14in"><IMG SRC="/assets/img/i_6e18d397cb89b0e0_html_6272f4c9.png" NAME="graphics22" ALIGN=BOTTOM WIDTH=199 HEIGHT=298 BORDER=0></p>
                            </TD>
                            <TD WIDTH=216 STYLE="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: none; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0in">
                                <p STYLE="margin-top: 0.14in"><IMG SRC="/assets/img/i_6e18d397cb89b0e0_html_m2d7218aa.png" NAME="graphics23" ALIGN=BOTTOM WIDTH=193 HEIGHT=289 BORDER=0></p>
                            </TD>
                            <TD WIDTH=215 STYLE="border: 1px solid #000000; padding: 0in 0.08in">
                                <UL>
                                    <LI><p STYLE="margin-top: 0.14in; margin-bottom: 0.14in">
                                            WUC treasurers can submit sales (after collecting the money from
                                            caretakers/scheme attendants – listed).</p>
                                    <LI><p STYLE="margin-top: 0.14in">The
                                            sales submitted are a reflection of the community collections
                                            submitted to the water board and saved.</p>
                                </UL>
                            </TD>
                        </TR>
                    </TABLE><BR><BR>
                    </p>
                    <p STYLE="margin-top: 0.14in; margin-bottom: 0.14in">
                        <BR><BR>
                    </p>
                    <p STYLE="margin-top: 0.14in; margin-bottom: 0.14in"><BR><BR>
                    </p>
                    <p STYLE="margin-top: 0.14in; margin-bottom: 0.14in"><BR><BR>
                    </p>
                    <DIV TYPE=FOOTER>
                        <p ALIGN=CENTER STYLE="margin-top: 0.47in; margin-bottom: 0in; line-height: 100%">
                        <SDFIELD TYPE=PAGE SUBTYPE=RANDOM FORMAT=PAGE>1</SDFIELD></p>
                        <p STYLE="margin-bottom: 0in; line-height: 100%"><BR>
                        </p>
                    </DIV>
                </div>
            </div>
        </div>
        <div class="site-footer">
            <div class="container">                
                <div class="copyright clearfix">
                    <p>
                        <b><?php echo SYSTEM_NAME; ?></b>                            
                    </p>
                    <p>&copy; <?php echo date("Y"); ?></p>
                </div>
            </div>
        </div>
        <script type="text/javascript">

            (function(i, s, o, g, r, a, m) {
                i['GoogleAnalyticsObject'] = r;
                i[r] = i[r] || function() {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
                a = s.createElement(o),
                        m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m)
            })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

            ga('create', 'UA-60032029-1', 'auto');
            ga('send', 'pageview');


        </script>
    </body>
</html>
