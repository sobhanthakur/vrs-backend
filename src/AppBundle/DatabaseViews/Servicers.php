<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 18/6/20
 * Time: 3:18 PM
 */

namespace AppBundle\DatabaseViews;


final class Servicers
{
    const vServicers = 'SELECT dbo.Servicers.ServicerID, dbo.Servicers.CustomerID, dbo.Servicers.LinkedCustomerID, dbo.Servicers.LinkedCustomerPin, dbo.Servicers.Name, dbo.Servicers.ServicerAbbreviation, dbo.Servicers.Email, 
                         dbo.Servicers.SendEmails, dbo.Servicers.Phone, dbo.Servicers.SendTexts, dbo.Servicers.FullBookingListSendSchedule, dbo.Servicers.FullBookingListSendDaysBefore, dbo.Servicers.LastMinuteBookingNotificationDays, 
                         dbo.Servicers.ViewTasksWithinDays, dbo.Servicers.IncludeGuestName, dbo.Servicers.IncludeGuestNumbers, dbo.Servicers.ServicerType, dbo.Servicers.AllowCreateCompletedTask, dbo.Servicers.TaskName, 
                         dbo.Servicers.IncludeDamage, dbo.Servicers.IncludeMaintenance, dbo.Servicers.IncludeLostAndFound, dbo.Servicers.IncludeServicerNote, dbo.Servicers.NotifyCustomerOnCompletion, dbo.Servicers.NotifyCustomerOnDamage,
                          dbo.Servicers.NotifyCustomerOnMaintenance, dbo.Servicers.NotifyCustomerOnServicerNote, dbo.Servicers.NotifyCustomerOnLostAndFound, dbo.Servicers.IncludeToOwnerNote, dbo.Servicers.DefaultToOwnerNote, 
                         dbo.Servicers.NotifyOwnerOnCompletion, dbo.Servicers.Password, dbo.Servicers.Password2, dbo.Servicers.WelcomeEmailSent, dbo.Servicers.WorkDays, dbo.Servicers.Active, dbo.Servicers.CreateDate, 
                         dbo.TimeZones.TimeZone, dbo.Servicers.TimeTracking, Servicers1.Name AS BackupServicer1Name, Servicers2.Name AS BackupServicer2Name, Servicers3.Name AS BackupServicer3Name, 
                         Servicers4.Name AS BackupServicer4Name, Servicers5.Name AS BackupServicer5Name, Servicers6.Name AS BackupServicer6Name, dbo.Servicers.BackupServicerID1, dbo.Servicers.BackupServicerID2, 
                         dbo.Servicers.BackupServicerID3, dbo.Servicers.BackupServicerID4, dbo.Servicers.BackupServicerID5, dbo.Servicers.BackupServicerID6, dbo.Servicers.BackupServicerID7, Servicers7.Name AS BackupServicer7Name, 
                         dbo.Servicers.AllowChangeTaskDate, dbo.Customers.Email AS CustomerEmail, dbo.Servicers.AllowAdminAccess, dbo.Servicers.AllowSetupAccess, dbo.Servicers.AllowAccountAccess, dbo.Servicers.AllowIssuesAccess, 
                         dbo.Servicers.AllowScheduleAccess, dbo.Servicers.AllowEditBookings, dbo.Servicers.AllowEditTasks, dbo.Servicers.AdminPassword, dbo.Servicers.AllowMasterCalendar, dbo.Servicers.AllowDragandDrop, 
                         dbo.Servicers.AllowEditNotes, dbo.Servicers.AllowTracking, dbo.Servicers.AllowReports, dbo.Servicers.AllowManage, dbo.Servicers.AllowQuickReports, dbo.Customers.GoLiveDate, dbo.Servicers.AlertOnMaintenance, 
                         dbo.Servicers.AlertOnDamage, dbo.Customers.QuickChangeAbbreviation, dbo.Servicers.ViewBookingsWithinDays, dbo.Plans.PlanType, dbo.Customers.LiveMode, dbo.Servicers.SortOrder, 
                         dbo.Servicers.ShowTaskTimeEstimates, dbo.Servicers.RequestAcceptTasks, dbo.TimeZones.Region AS TimeZoneRegion, dbo.Customers.ShowStartTimeOnDashboard, dbo.Servicers.AllowShareImagesWithOwners, 
                         dbo.Servicers.AllowCreateOneOffTask, dbo.Servicers.AllowImageUpload, dbo.Customers.BusinessName, dbo.Servicers.IncludeSupplyFlag, dbo.Customers.UseBeHome247, dbo.Customers.BeHome247Key, 
                         dbo.Customers.BeHome247Secret, dbo.Countries.CountryID, dbo.Countries.CountryPhoneCode, dbo.Servicers.ScheduleNote1, dbo.Servicers.ScheduleNote2, dbo.Servicers.ScheduleNote3, dbo.Servicers.ScheduleNote4, 
                         dbo.Servicers.ScheduleNote5, dbo.Servicers.ScheduleNote7, dbo.Servicers.ScheduleNote6, dbo.Customers.ShowPiecePayAmountsOnEmployeeDashboards, dbo.Servicers.SendOwnerBookingNotes, 
                         dbo.Servicers.SendTaskListNightBefore, dbo.Servicers.SendTaskListDayOf, dbo.Servicers.SendTaskListWeekly, dbo.Servicers.SendTaskListWeeklyDay, dbo.Servicers.TimeTrackingMileage, dbo.Servicers.IncludeUrgentFlag, 
                         dbo.Servicers.NotifyCustomerOnSupplyFlag, dbo.Servicers.TimeTrackingGPS, dbo.Servicers.ShowIssuesLog, dbo.Servicers.NotifyIfUrgent, dbo.Servicers.NotifyOnCompletion, dbo.Servicers.NotifyOnDamage, 
                         dbo.Servicers.NotifyOnMaintenance, dbo.Servicers.NotifyOnLostAndFound, dbo.Servicers.NotifyOnSupplyFlag, dbo.Servicers.NotifyOnServicerNote, dbo.Servicers.NotifyOnNotYetDone, dbo.Servicers.NotifyOnNotYetDoneHours, 
                         dbo.Servicers.NotifyOnOverdue, dbo.Servicers.NotifyOnAccepted, dbo.Servicers.NotifyOnDeclined, dbo.Servicers.NotifyOnCheckout, dbo.Servicers.TimeZoneID, dbo.Servicers.AllowStartEarly, 
                         dbo.Servicers.ScheduleNote1Show, dbo.Servicers.ScheduleNote2Show, dbo.Servicers.ScheduleNote3Show, dbo.Servicers.ScheduleNote4Show, dbo.Servicers.ScheduleNote5Show, dbo.Servicers.ScheduleNote6Show, 
                         dbo.Servicers.ScheduleNote7Show, dbo.Servicers.AllowIssuesEdit, dbo.Servicers.AllowEditTaskPiecePay, dbo.Servicers.AllowServiceAssignmentAccess, dbo.Customers.SortQuickChangeToTop, dbo.Servicers.LanguageID, 
                         dbo.Servicers.IncludeGuestEmailPhone, dbo.Servicers.AllowSetupEmployees, dbo.Servicers.AllowViewRentDeposit, dbo.Servicers.AllowEditRentDeposit, dbo.Servicers.AllowAddStandardTask, dbo.Servicers.PayRate, 
                         dbo.Customers.Active AS CustomerActive,dbo.Customers.IncludeHouseKeeping
                         FROM dbo.Plans RIGHT OUTER JOIN
                         dbo.Customers ON dbo.Plans.PlanID = dbo.Customers.PlanID RIGHT OUTER JOIN
                         dbo.Countries RIGHT OUTER JOIN
                         dbo.TimeZones RIGHT OUTER JOIN
                         dbo.Servicers ON dbo.TimeZones.TimeZoneID = dbo.Servicers.TimeZoneID ON dbo.Countries.CountryID = dbo.Servicers.CountryID ON dbo.Customers.CustomerID = dbo.Servicers.CustomerID LEFT OUTER JOIN
                         dbo.Servicers AS Servicers7 ON dbo.Servicers.BackupServicerID7 = Servicers7.ServicerID LEFT OUTER JOIN
                         dbo.Servicers AS Servicers1 ON dbo.Servicers.BackupServicerID1 = Servicers1.ServicerID LEFT OUTER JOIN
                         dbo.Servicers AS Servicers2 ON dbo.Servicers.BackupServicerID2 = Servicers2.ServicerID LEFT OUTER JOIN
                         dbo.Servicers AS Servicers3 ON dbo.Servicers.BackupServicerID3 = Servicers3.ServicerID LEFT OUTER JOIN
                         dbo.Servicers AS Servicers4 ON dbo.Servicers.BackupServicerID4 = Servicers4.ServicerID LEFT OUTER JOIN
                         dbo.Servicers AS Servicers5 ON dbo.Servicers.BackupServicerID5 = Servicers5.ServicerID LEFT OUTER JOIN
                         dbo.Servicers AS Servicers6 ON dbo.Servicers.BackupServicerID6 = Servicers6.ServicerID';
}