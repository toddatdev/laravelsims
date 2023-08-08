<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta name="viewport" content="width=device-width" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title></title>
        <style type="text/css">
            .indented {
                padding-left: 50pt;
                padding-right: 50pt;
                color: blue;
            }
        </style>
    </head>

    <body>
        <h3>{{ trans('labels.enrollment.confirm_email_subject') }}</h3>
        <p>{!! trans('labels.enrollment.confirm_email_text', (['siteHelpEmail'=>Session::get('site_email'), 'eventText'=>$enrollmentRequest->event->DisplayEventNameShort])) !!}</p>
   </body>
