#!/bin/sh

echo 'HTTP/1.1 200 OK'
echo

export $(echo "$QUERY_STRING" | tr "&" " ")
case $DISC in
	usb)
		DISCSECTION="usbdisc"
	;;
	network1)
		DISCSECTION="disc0"
	;;
	network2)
		DISCSECTION="disc1"
	;;
	*)
		exit
	;;
esac

MOUNTPOINT=$(txtconfig /etc/streams.ini ${DISCSECTION} mountpoint)
mountpoint -q ${MOUNTPOINT} || exit

DEVICEID=$(txtconfig /etc/streams.ini common deviceid)
JPEGDATA="jpegdata"

ls -1r ${MOUNTPOINT}/${DEVICEID}/${JPEGDATA}/ | head -n 25