@extends('emails.layouts.emailMaster')
@section('content')

<table bgcolor="#FFFFFF"  border="0" cellpadding="0" cellspacing="0" width="600" id="emailBody">

    <!-- MODULE ROW // -->
    <!--
        To move or duplicate any of the design patterns 
        in this email, simply move or copy the entire
        MODULE ROW section for each content block.
    -->
    <tr>
        <td align="center" valign="top">
            <!-- CENTERING TABLE // -->
            <!--
                The centering table keeps the content
                tables centered in the emailBody table,
                in case its width is set to 100%.
            -->
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="color:#868686;" bgcolor="#FFFFFF">
                <tr>
                    <td align="center" valign="top">
                        <!-- FLEXIBLE CONTAINER // -->
                        <!--
                            The flexible container has a set width
                            that gets overridden by the media query.
                            Most content tables within can then be
                            given 100% widths.
                        -->
                        <table border="0" cellpadding="0" cellspacing="0" width="600" class="flexibleContainer">
                            <tr>
                                <td align="center" valign="top" width="600" class="flexibleContainerCell">

                                    <!-- CONTENT TABLE // -->
                                    <!--
                                    The content table is the first element
                                        that's entirely separate from the structural
                                        framework of the email.
                                    -->
                                    <table border="0" cellpadding="10" cellspacing="0" width="100%">
                                        <tr>
                                            <td align="center" valign="top" class="textContent">
                                                <img src="https://customblackindex.com/images/logo.png" width="30%" />
                                                <h1 style="color:#868686;line-height:100%;font-family:Helvetica,Arial,sans-serif;font-size:35px;font-weight:normal;margin-bottom:5px;text-align:center;">{{\GlobalVars::SITE_ADDRESS_NAME}}</h1>
                                                <h2 style="text-align:center;font-weight:normal;font-family:Helvetica,Arial,sans-serif;font-size:23px;margin-bottom:10px;color:#205478;line-height:130%;">
                                                    {{ $emailHeaderSubject }}
                                                </h2>
                                                <!-- <div style="text-align:center;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#FFFFFF;line-height:135%;">
                                                </div> -->
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- // CONTENT TABLE -->

                                </td>
                            </tr>
                        </table>
                        <!-- // FLEXIBLE CONTAINER -->
                    </td>
                </tr>
            </table>
            <!-- // CENTERING TABLE -->
        </td>
    </tr>
    <!-- // MODULE ROW -->


    <!-- MODULE ROW // -->
    <!--  The "mc:hideable" is a feature for MailChimp which allows
        you to disable certain row. It works perfectly for our row structure.
        http://kb.mailchimp.com/article/template-language-creating-editable-content-areas/
    -->
    <tr mc:hideable>
        <td align="center" valign="top">
            <!-- CENTERING TABLE // -->
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td align="center" valign="top">
                        <!-- FLEXIBLE CONTAINER // -->
                        <table border="0" cellpadding="30" cellspacing="0" width="600" class="flexibleContainer">
                            <tr>
                                <td valign="top" width="600" class="flexibleContainerCell">

                                    <!-- CONTENT TABLE // -->
                                    <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                            <td align="left" valign="top" class="flexibleContainerBox">
                                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 100%;">
                                                    <tr>
                                                        <td align="left" class="textContent">
                                                            <!-- <h3 style="color:#5F5F5F;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;"></h3> -->
                                                            <div style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#5F5F5F;line-height:135%;">Hello {{$userName}},<br/><br/>
                                                                You have requested to reset your login password. Click here {{$url}}<br/><br/>Thanks & Regards<br>
                                                               Custom Black Index Team</div>
                                                        </td>
                                                    </tr>
                                                    <hr>
                                                    <br>
                                                     <tr>
                                                        <td align="center" class="textContent">
                                                            <!-- <h3 style="color:#5F5F5F;line-height:125%;font-family:Helvetica,Arial,sans-serif;font-size:20px;font-weight:normal;margin-top:0;margin-bottom:3px;text-align:left;"></h3> -->
                                                            
                                                            <div style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#5F5F5F;line-height:135%;">
                                                                <p>Copyright © 2021| Custom Black Index LTD | All rights reserved.
                                                                Our mailing address is:
                                                                <a href="mailto:info@customblackindex.com">info@customblackindex.com</a></p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- // CONTENT TABLE -->

                                </td>
                            </tr>
                               <tr style="text-align:center;">
                                                        <td style="text-align:center;">
                                                             <div align="center" style="text-align:center; width:200px; margin:auto;">
                                                        <a href="https://www.facebook.com/groups/1681550245342485/" target="_blank" style="padding:5px;"><img src="<?php echo url('/'); ?>/img/fb.png" style="width:20px;"></a>
                                                        <a href="https://www.instagram.com/customblackindex/" target="_blank" style="padding:5px;"><img src="<?php echo url('/'); ?>/img/insta.png" style="width:20px;"></a>
                                                       </div></td>
                                                    </tr>
                        </table>
                        <!-- // FLEXIBLE CONTAINER -->
                    </td>
                </tr>
            </table>
            <!-- // CENTERING TABLE -->
        </td>
    </tr>
    <!-- // MODULE ROW -->


    <!-- MODULE ROW // -->
    <tr>
        <td align="center" valign="top">
            <!-- CENTERING TABLE // -->
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr style="padding-top:0;">
                    <td align="center" valign="top">
                        <!-- FLEXIBLE CONTAINER // -->
                        <!--<table border="0" cellpadding="30" cellspacing="0" width="500" class="flexibleContainer">
                            <tr>
                                <td style="padding-top:0;" align="center" valign="top" width="500" class="flexibleContainerCell">

                                    <a href="{!!$url!!}" style="text-decoration:none;">
                                       
                                        <table border="0" cellpadding="0" cellspacing="0" width="50%" class="emailButton" style="background-color: #e85023;">
                                            <tr>
                                                <td align="center" valign="middle" class="buttonContent" style="padding-top:15px;padding-bottom:15px;padding-right:15px;padding-left:15px;">
                                                    <a style="color:#FFFFFF;text-decoration:none;font-family:Helvetica,Arial,sans-serif;font-size:20px;line-height:135%;" href="{!!$url!!}" target="_blank">Reset Now</a>
                                                </td>
                                            </tr>
                                        </table>
                                       
                                    </a>

                                </td>
                            </tr>
                        </table>-->
                        <!-- // FLEXIBLE CONTAINER -->
                    </td>
                </tr>
            </table>
            <!-- // CENTERING TABLE -->
        </td>
    </tr>
    <!-- // MODULE ROW -->

</table>
@endsection