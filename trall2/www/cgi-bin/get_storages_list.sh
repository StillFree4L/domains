#!/bin/sh

echo 'HTTP/1.1 200 OK'
echo

while read LINE
do
    echo "$LINE"
done <<EOF
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>3</title>
    <style type="text/css">
        a { color: blue } /* цвет ссылок */
        .one { text-decoration: none } /* убираем подчеркивание */
        .one:hover { text-decoration: underline } /* добавляем подчеркивание при наведении */
    </style>
</head>
<body>

<script type="text/javascript">
function active_link_color(a) {
  var aa = document.getElementsByTagName('a');
  for(var i in aa) {
    if(aa[i] != a && aa[i].className == 'one') {
      aa[i].style.color = 'blue';
      aa[i].style.backgroundColor = 'white';
    }
  }
  a.style.color='white';
  a.style.backgroundColor = 'blue';
}
</script>

<pre>
EOF

MOUNTPOINT_SIGN_OFF='<font color="red">&#10006;</font>'
MOUNTPOINT_SIGN_ON='<font color="green">&#10004;</font>'

MEDIA0="usbdisc" ; MOUNTDIR0="USB"
MEDIA1="disc0"   ; MOUNTDIR1="Network1"
MEDIA2="disc1"   ; MOUNTDIR2="Network2"

for I in 0 1 2 ; do
    DISC=$(eval echo \$"MEDIA"$I)

    DIR=$(eval echo \$"MOUNTDIR"$I)
    MOUNTPOINT=$(txtconfig /etc/streams.ini ${DISC} mountpoint)

    mountpoint -q ${MOUNTPOINT}
    [ $? -eq 0 ] && echo -n "${MOUNTPOINT_SIGN_ON}" || echo -n "${MOUNTPOINT_SIGN_OFF}"
    echo -n '<a href="'"/cgi-bin/get_images_list.sh?DISC=$DISC&FMEDIA=${MOUNTPOINT}"'" target="frame_images_list"'
    echo -n ' class="one"'
    echo -n ' onclick="'"active_link_color(this);"
    echo -n "parent.document.getElementById('fr_log').src='';"
    echo -n "active_link_color(this);parent.document.getElementById('fr_image').src='';"
    echo -n '"'
    echo '>'${DIR}'</a>'
done

while read LINE
do
    echo "$LINE"
done <<EOF
</pre>

</body>
</html>
EOF

echo -ne "\r\r"