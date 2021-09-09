<?php

namespace App\Classes;

class GlobalVars
{
    /**************************************
    /*
    /* Start declaration of Constants
    /*
     ***************************************/
    public const ACTIVE_STATUS                        = 'Active';
    public const INACTIVE_STATUS                      = 'Inactive';
    public const ADMIN_USER_TYPE                      = 'A';
    public const TEACHER_USER_TYPE                    = 'T';
    public const STUDENT_USER_TYPE                    = 'S';
    public const PARENT_USER_TYPE                     = 'P';
    //public const SITE_USER_TYPE                       = 'U';
    public const FRONT_USER_TYPES                     = ['P'=>'Parent', 'S'=>'Student', 'T'=>'Teacher'];
    public const ADMIN_NAME                           = 'Directory APP';
    //public const SITE_ADDRESS_NAME                    = 'directoryapp.com';
    public const SITE_ADDRESS_NAME                    = 'Custom Black Index';
    public const ADMIN_LOGO_MINI                      = 'CS';
    public const ADMIN_RECORDS_PER_PAGE               = 10;
    public const FRONT_USER_RECORDS_PER_PAGE          = 10;
    public const ADMIN_BASEURL                        = '/da-admin/';  // Local path
    public const ADMIN_SITE_URL                       = 'http://localhost/directoryapp/public/da-admin/';  // Local path

    public const FRONT_SITE_URL                       = '';

    public const UP_IMAGE_TYPES                       = 'jpeg,png,jpg';
    // public const UP_IMAGE_PATH_MAIN                   = '/uploads/main/';
    public const ADMIN_USER_LOGO_PATH                 = '/uploads/adminuser_logos/';
    public const SCHOOL_LOGO_PATH                     = '/uploads/school_logos/';
    public const CHARITY_LOGO_PATH                     = '/uploads/chaity_logos/';
    
}
