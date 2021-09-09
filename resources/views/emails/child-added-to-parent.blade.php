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
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="color:#FFFFFF;" bgcolor="#10255b">
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
                                                <h1 style="color:#FFFFFF;line-height:100%;font-family:Helvetica,Arial,sans-serif;font-size:35px;font-weight:normal;margin-bottom:5px;text-align:center;">{{\GlobalVars::SITE_ADDRESS_NAME}}</h1>
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
                                                            <div style="text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#5F5F5F;line-height:135%;">
                                                            Dear {{ $email_user_name }},<br/><br/>
                                                            
                                                            {{ $content_message }}<br/><br/>


                                                                <br/>

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
                        </table>
                        <!-- // FLEXIBLE CONTAINER -->
                    </td>
                </tr>
            </table>
            <!-- // CENTERING TABLE -->
        </td>
    </tr>

</table>
@endsection