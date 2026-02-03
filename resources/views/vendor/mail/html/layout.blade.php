<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<title>{{ config('app.name') }}</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="color-scheme" content="light">
<meta name="supported-color-schemes" content="light">
<style>
@media only screen and (max-width: 600px) {
.inner-body {
width: 100% !important;
}

.footer {
width: 100% !important;
}
}

@media only screen and (max-width: 500px) {
.button {
width: 100% !important;
}
}
        </style>
        {!! $head ?? '' !!}
    </head>
    <body style="margin: 0; padding: 0; background-color: #f9fafb; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;">

        <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color: #f9fafb; margin: 0; padding: 40px 20px; width: 100%;">
            <tr>
                <td align="center">
                    <table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="max-width: 600px; margin: 0 auto;">
                        <!-- Email Body -->
                        <tr>
                            <td class="body" width="100%" cellpadding="0" cellspacing="0">
                                <table class="inner-body" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color: #ffffff; border-radius: 12px; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06); overflow: hidden; border: 1px solid #e5e7eb;">
                                    {!! $header ?? '' !!}
                                    <!-- Body content -->
                                    <tr>
                                        <td class="content-cell" style="padding: 0;">
                                            <table class="message-card" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                                <tr>
                                                    <td class="message-card-accent" width="6"></td>
                                                    <td class="message-card-body">
                                                        {!! Illuminate\Mail\Markdown::parse($slot) !!}

                                                        {!! $subcopy ?? '' !!}
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        {!! $footer ?? '' !!}
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
