@extends('emails.emogrify')

@section('html')
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width"/>
  <style>{!! file_get_contents(config('mail.css')) !!}</style>
</head>
<body>
  <table class="body">
    <tr>
      <td class="center" align="center" valign="top">
        <center>

          <table class="row">
            <tr>
              <td class="center" align="center">
                <center>

                  <table class="container">
                    <tr>
                      <td class="wrapper last">

                        <table class="twelve columns">
                          <tr>
                            <td>
                              <img src="{{ asset('/assets/images/deakin-digital-logo.png') }}" style="width:150px !important; padding-top: 20px; padding-bottom: 20px;">
                            </td>
                            <td class="expander"></td>
                          </tr>
                        </table>

                      </td>
                    </tr>
                  </table>

                </center>
              </td>
            </tr>
          </table>

          <table class="row" style="background-color: #F2F6F8;">
            <tr>
              <td>
                <table class="container">
                  <tr>
                    <td class="wrapper last">
                      <table class="twelve columns">
                        <tr>
                          <td>
                            <div class="content">
                              @yield('content')
                            </div>
                          </td>
                          <td class="expander"></td>
                        </tr>
                      </table>

                    </td>
                  </tr>
                </table>
              <!-- container end below -->
              </td>
            </tr>
          </table>

          <table class="row">
            <tr>
              <td>
                <table class="container">
                  <tr>
                    <td class="wrapper last">
                      <table class="twelve columns">
                        <tr>
                          <td style="padding-top: 20px; color: grey;" class="disclaimer">
                            <p>&nbsp;</p>
                            {!! Filter::filter(Variable::get('site.email.footer', ''), ['purifier' => 'full_html']) !!}
                          </td>
                          <td class="expander"></td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>

        </center>
      </td>
    </tr>
  </table>
</body>
</html>
@endsection