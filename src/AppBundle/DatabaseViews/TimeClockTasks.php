<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 4/5/20
 * Time: 7:03 PM
 */

namespace AppBundle\DatabaseViews;


final class TimeClockTasks
{
    const vTimeClockTasks = 'SELECT        dbo.TimeClockTasks.TimeClockTaskID, dbo.TimeClockTasks.TaskID, dbo.TimeClockTasks.ClockIn, dbo.TimeClockTasks.ServicerID, dbo.TimeClockTasks.ClockOut, dbo.Servicers.Name, dbo.Servicers.CustomerID, 
                         dbo.Services.ServiceName, dbo.Tasks.PropertyID, dbo.Tasks.TaskName, dbo.Properties.PropertyName, dbo.Properties.PropertyAbbreviation, DATEDIFF(s, dbo.TimeClockTasks.ClockIn, ISNULL(dbo.TimeClockTasks.ClockOut, 
                         getUTCDate())) AS timeTaken, dbo.PropertyBookings.NumberOfGuests, dbo.PropertyBookings.NumberOfChildren, dbo.PropertyBookings.NumberOfPets, dbo.Services.ServiceID, dbo.Properties.OwnerID, 
                         dbo.TimeClockTasks.Note, dbo.TimeClockTasks.AutoLogOutFlag, dbo.Tasks.MinTimeToComplete, dbo.Tasks.MaxTimeToComplete, dbo.Customers.CustomerID AS Expr1, dbo.Customers.GoLiveDate, TimeZones_1.TimeZone, 
                         dbo.Tasks.TaskDescription, dbo.Tasks.ServicerNotes, dbo.Services.Abbreviation, ISNULL(TimeZones_1.Region, dbo.TimeZones.Region) AS TimeZoneRegion, dbo.Tasks.CompleteConfirmedDate, dbo.TimeClockTasks.InLat, 
                         dbo.TimeClockTasks.InLon, dbo.TimeClockTasks.OutLat, dbo.TimeClockTasks.OutLon, dbo.TimeClockTasks.InIsMobile, dbo.TimeClockTasks.InAccuracy, dbo.TimeClockTasks.OutAccuracy, dbo.TimeClockTasks.OutIsMobile, 
                         dbo.TimeZones.TimeZone AS TimeZoneCustomer, dbo.TimeZones.Region AS TimeZoneCustomerRegion, dbo.Properties.lat, dbo.Properties.lon, dbo.Servicers.ServicerAbbreviation, dbo.Servicers.ServicerType
FROM            dbo.Servicers FULL OUTER JOIN
                         dbo.Customers LEFT OUTER JOIN
                         dbo.TimeZones ON dbo.Customers.TimeZoneID = dbo.TimeZones.TimeZoneID ON dbo.Servicers.CustomerID = dbo.Customers.CustomerID FULL OUTER JOIN
                         dbo.TimeClockTasks LEFT OUTER JOIN
                         dbo.Regions RIGHT OUTER JOIN
                         dbo.TimeZones AS TimeZones_1 ON dbo.Regions.TimeZoneID = TimeZones_1.TimeZoneID RIGHT OUTER JOIN
                         dbo.Properties ON dbo.Regions.RegionID = dbo.Properties.RegionID RIGHT OUTER JOIN
                         dbo.PropertyBookings RIGHT OUTER JOIN
                         dbo.Tasks ON dbo.PropertyBookings.PropertyBookingID = dbo.Tasks.PropertyBookingID LEFT OUTER JOIN
                         dbo.Services ON dbo.Tasks.ServiceID = dbo.Services.ServiceID ON dbo.Properties.PropertyID = dbo.Tasks.PropertyID ON dbo.TimeClockTasks.TaskID = dbo.Tasks.TaskID ON 
                         dbo.Servicers.ServicerID = dbo.TimeClockTasks.ServicerID';
}