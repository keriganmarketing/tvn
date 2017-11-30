<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        * { font-family: sans-serif; }
        h2 { color: #D85035; font-weight:bold; }
        p { color: #555; font-size: 14px; }
        table.data { background-color: #fff; }
        table.data td { background-color: #fdfdfd; border: 1px solid #FFF; font-size: 14px; color: #555; padding:4px 6px; }
        table.data td.label { font-weight:bold; width:30%; }
        a { color: #D85035; }
    </style>
</head>
<body bgcolor="#EAEAEA" style="background-color:#EAEAEA;">
<table
    cellpadding="0"
    cellspacing="0"
    border="0"
    align="center"
    style="width:650px;
    background-color:#FFFFFF;
    margin:30px auto;"
    bgcolor="#FFFFFF" >
    <tbody>
    <tr>
        <td style="padding:20px; border:1px solid #ddd; border-top:10px solid #123F7B; border-bottom: 0;" >
            <h2>{headline}</h2>
            <p>{introcopy}</p>

            <table class="data" cellpadding="0" cellspacing="0" border="0" style="width:100%;" >
                <tbody>
                {data}
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td style="border-bottom: #D85035 solid 2px; border-left:1px solid #ddd; border-right:1px solid #ddd; padding:10px;">
            <p style="text-align:center; color:#999; font-family:sans-serif; font-size:12px;"><strong>Submitted on:</strong> {datetime}</p>
        </td>
    </tr>
    </tbody>
</table>
<p style="text-align:center; color:#999; font-family:sans-serif; font-size:12px;">This email was sent by <a href="{url}">{website}</a>.</p>
<p style="text-align:center; color:#999; font-family:sans-serif; font-size:12px;">&copy; {copyright}</p>
</body>
</html>
