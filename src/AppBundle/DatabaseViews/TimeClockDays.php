<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 15/5/20
 * Time: 5:28 PM
 */

namespace AppBundle\DatabaseViews;


final class TimeClockDays
{
    const vTimeClockDays = 'SELECT        dbo.TimeClockDays.ServicerID, dbo.TimeClockDays.ClockIn, dbo.TimeClockDays.ClockOut, dbo.Servicers.Name, dbo.Servicers.CustomerID, dbo.Servicers.ServicerType, dbo.TimeClockDays.TimeClockDayID, DATEDIFF(s, 
                         dbo.TimeClockDays.ClockIn, ISNULL(dbo.TimeClockDays.ClockOut, getUTCDate())) AS timeTaken, dbo.TimeClockDays.autoLogOutFlag, dbo.Customers.GoLiveDate, dbo.TimeZones.TimeZone, dbo.TimeClockDays.MileageIn, 
                         dbo.TimeClockDays.MileageOut, dbo.Servicers.TimeTrackingMileage, dbo.TimeZones.Region AS TimeZoneRegion, dbo.TimeClockDays.InLat, dbo.TimeClockDays.InLon, dbo.TimeClockDays.OutLat, dbo.TimeClockDays.OutLon, 
                         dbo.TimeClockDays.InIsMobile, dbo.TimeClockDays.InAccuracy, dbo.TimeClockDays.OutIsMobile, dbo.TimeClockDays.OutAccuracy
FROM            dbo.TimeZones RIGHT OUTER JOIN
                         dbo.Servicers ON dbo.TimeZones.TimeZoneID = dbo.Servicers.TimeZoneID LEFT OUTER JOIN
                         dbo.Customers ON dbo.Servicers.CustomerID = dbo.Customers.CustomerID RIGHT OUTER JOIN
                         dbo.TimeClockDays ON dbo.Servicers.ServicerID = dbo.TimeClockDays.ServicerID';
}