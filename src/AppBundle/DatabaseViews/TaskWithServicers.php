<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 11/5/20
 * Time: 12:19 PM
 */

namespace AppBundle\DatabaseViews;


final class TaskWithServicers
{
    const vTasksWithServicers = 'SELECT        Tasks_1.TaskID, Tasks_1.PropertyBookingID, Tasks_1.PropertyID, Tasks_1.TaskName, Tasks_1.TaskDescription, Tasks_1.TaskDate, Tasks_1.TaskTime, Tasks_1.TaskStartDate, Tasks_1.TaskStartTime, 
                         Tasks_1.TaskCompleteByDate, Tasks_1.TaskCompleteByTime, Tasks_1.TaskFirstStartDate, Tasks_1.TaskFirstStartTime, Tasks_1.LastEventDay, Tasks_1.LastEventTime, Tasks_1.LastEventIsCheckin, Tasks_1.ServiceID, 
                         Tasks_1.MinTimeToComplete, Tasks_1.MaxTimeToComplete, Tasks_1.ActualTimeToComplete, Tasks_1.HasLostAndFound, Tasks_1.Closed, Tasks_1.SortOrder, Tasks_1.CompleteConfirmationRequestedDate, 
                         Tasks_1.CompleteConfirmedDate, Tasks_1.ToOwnerNote, Tasks_1.ToCustomerNote, Tasks_1.ServicerNotes, Tasks_1.PropertyManagerNotes, Tasks_1.Marked, Tasks_1.Edited, Tasks_1.Priority, Tasks_1.Active, 
                         Tasks_1.CreateDate, Tasks_1.UpdateDate, Tasks_1.DeletedDate, dbo.PropertyBookings.CheckIn, dbo.PropertyBookings.CheckInTime, dbo.PropertyBookings.CheckOut, dbo.PropertyBookings.CheckOutTime, 
                         dbo.PropertyBookings.Guest, dbo.PropertyBookings.NumberOfGuests, dbo.PropertyBookings.NumberOfPets, dbo.PropertyBookings.NewBooking, dbo.PropertyBookings.BackToBackStart, dbo.PropertyBookings.BackToBackEnd, 
                         dbo.PropertyBookings.IsManuallyEntered, dbo.PropertyBookings.GlobalNote, dbo.PropertyBookings.InternalNote, dbo.PropertyBookings.UpdateDate AS PropertyBookingUpdateDate, 
                         dbo.PropertyBookings.CreateDAte AS PropertyBookingCreateDate, dbo.Services.ServiceGroupID, dbo.Services.ServiceName, dbo.Services.Abbreviation, dbo.Services.ShortDescription, dbo.Services.ChangeOverDays, 
                         dbo.Services.SkipOnMaxChangeDays, dbo.Services.MaxDaysBeforeToComplete, dbo.Services.MaxDaysToComplete, dbo.Services.CompleteTime, dbo.Services.MidStayDays, dbo.Services.MidVacancyDays, 
                         dbo.Services.MidStayDaysBeforeStart, dbo.Services.MidDayOfWeekStart, dbo.Services.MidStayDaysAfterEnd, dbo.Services.MidStayDaysToComplete, dbo.Services.MidVacancyDaysBeforeStart, 
                         dbo.Services.MidVacancyDaysAfterEnd, dbo.Services.MidVacancyDaysToComplete, dbo.Services.ScheduleRecurrance, dbo.Services.ScheduleType, dbo.Services.ScheduleDay, dbo.Services.ScheduleStartTime, 
                         dbo.Services.ScheduleNumDays, dbo.Services.ScheduleEndTime, dbo.Services.CreateDate AS ServicesCreateDate, dbo.Services.CompletionRequired, dbo.Services.RequestAcceptanceOnAssignment, 
                         dbo.Services.CompletionRequest, dbo.Services.DayBeforeReminder, dbo.Services.ReminderReceivedRequest, dbo.Services.NotifyCustomerOnReminderReceived, dbo.Services.IncludeToCustomerNote, 
                         dbo.Services.DefaultToCustomerNote, dbo.Services.Active AS ServicesActive, dbo.Properties.RegionID, dbo.Properties.OwnerID, dbo.Properties.iCalLink, dbo.Properties.iCalLinkStatus, dbo.Properties.iCalLinkTry, 
                         dbo.Properties.iCalLink4, dbo.Properties.iCalLink4Status, dbo.Properties.iCalLinkTry4, dbo.Properties.iCalLink3, dbo.Properties.iCalLink3Status, dbo.Properties.iCalLinkTry3, dbo.Properties.iCalLink2, 
                         dbo.Properties.iCalLink2Status, dbo.Properties.iCalLinkTry2, dbo.Properties.JSON, dbo.Properties.JSONStatus, dbo.Properties.JsonTry, dbo.Properties.PerformingImport, dbo.Properties.PerformingImportDate, 
                         dbo.Properties.ImportIssueCount, dbo.Properties.ImportIssueNote, dbo.Properties.PropertyName, dbo.Properties.DefaultCheckInTime, dbo.Properties.DefaultCheckOutTime, dbo.Properties.Address, 
                         dbo.Properties.PropertyNotes, dbo.Properties.DoorCode, dbo.Properties.CreateDate AS PropertiesCreateDate, dbo.Regions.Region, dbo.Regions.CreateDate AS RegionsCreateDate, dbo.TasksToServicers.TaskToServicerID, 
                         dbo.TasksToServicers.ServicerID, dbo.TasksToServicers.IsLead, dbo.TasksToServicers.SentInTaskListDate, dbo.TasksToServicers.BookingNotifiedDate, dbo.TasksToServicers.BookingConfirmedDate, 
                         dbo.TasksToServicers.BookingDeclinedDate, dbo.TasksToServicers.ReminderNotifiedDate, dbo.TasksToServicers.ReminderConfirmedDate, dbo.TasksToServicers.CreateDate AS TasksToServicersCreateDate, 
                         Servicers_2.Name, Servicers_2.Email, Servicers_2.SendEmails, Servicers_2.Phone, Servicers_2.SendTexts, dbo.PropertyBookings.Source, dbo.Owners.OwnerName, dbo.Owners.OwnerEmail, dbo.Owners.OwnerPhone, 
                         Servicers_2.ViewTasksWithinDays, Tasks_1.ClosedDate, Tasks_1.Abbreviation AS TaskAbbreviation, Customers_1.TimeZoneID, dbo.TimeZones.TimeZone, Servicers_2.LastMinuteBookingNotificationDays, 
                         Servicers_2.Password, Customers_1.LiveMode, dbo.Owners.SendEmails AS OwnerSendEmails, dbo.Owners.SendTexts AS OwnerSendTexts, Tasks_1.TaskDateTime, Tasks_1.NumberOfServicers, 
                         Servicers_2.IncludeGuestName, Servicers_2.IncludeGuestNumbers, dbo.Properties.PropertyAbbreviation, Servicers_2.ServicerAbbreviation, Tasks_1.NeedsMaintenance, Tasks_1.HasDamage, dbo.Properties.Image1, 
                         dbo.Properties.Image2, dbo.Properties.PropertyFile, dbo.Properties.Description, dbo.Regions.Color, dbo.Properties.SortOrder AS PropertySortOrder, dbo.Regions.SortOrder AS RegionSortOrder, Servicers_2.WorkDays, 
                         dbo.Services.NotifyOnAssignment, Tasks_1.NotifyCustomerOnCompletion, Tasks_1.NotifyCustomerOnOverdue, Tasks_1.NotifyCustomerOnDamage, Tasks_1.NotifyCustomerOnMaintenance, 
                         Tasks_1.NotifyCustomerOnLostAndFound, Tasks_1.NotifyCustomerOnServicerNote, Tasks_1.IncludeDamage, Tasks_1.IncludeMaintenance, Tasks_1.IncludeLostAndFound, Tasks_1.IncludeServicerNote, 
                         Tasks_1.NotifyServicerOnOverdue, Tasks_1.NotifyCustomerOnNotYetDone, Tasks_1.NotifyServicerOnNotYetDone, Tasks_1.NotifyOwnerOnCompletion, Tasks_1.IncludeToOwnerNote, Tasks_1.DefaultToOwnerNote, 
                         Servicers_2.ServicerType, Tasks_1.Image1 AS TaskImage1, Tasks_1.Image2 AS TaskImage2, Tasks_1.Image3 AS TaskImage3, Customers_1.GoLiveDate, Servicers_1.Name AS LinkedVendor, 
                         dbo.PropertyBookings.NumberOfChildren, Tasks_1.AllowShareImagesWithOwners, dbo.Regions.RegionID AS Expr1, Tasks_1.Image1ShowOwner, Tasks_1.Image2ShowOwner, Tasks_1.Image3ShowOwner, 
                         Servicers_2.AllowChangeTaskDate, Servicers_1.LinkedCustomerID, Servicers_1.TaskName AS VendorTaskName, Servicers_1.ServicerID AS VendorServicerID, Servicers_1.CustomerID AS VendorCustomerID, 
                         dbo.Properties.LinkedPropertyID, Properties_1.PropertyName AS LinkedPropertyName, dbo.Properties.CustomerID, Customers_1.StartNotificationTime, Customers_1.EndNotificationTime, dbo.TasksToServicers.PiecePay, 
                         Properties_1.PropertyAbbreviation AS LinkedPropertyAbbreviation, dbo.PropertyBookings.color AS BookingColor, Customers_1.DayEndTime, Tasks_1.CompletedByServicerID, 
                         dbo.Servicers.Name AS CompletedByServicerName, dbo.Servicers.Email AS CompletedByServicerEmail, dbo.Servicers.Phone AS CompletedByServicerPhone, dbo.PropertyBookings.PropertyID AS PropertyBookingPropertyID, 
                         Properties_1.CustomerID AS LinkedPropertyCustomerID, dbo.Services.ScheduleVacantOnly, Tasks_1.ManagerServicerID, Customers_1.QuickChangeAbbreviation, Tasks_1.ParentTaskID, dbo.Services.AlertOnParentCompletion, 
                         dbo.Services.AllowStartBeforeParentComplete, Services_1.ServiceName AS ParentServiceName, dbo.Services.ParentServiceID, dbo.Tasks.TaskStartDate AS ParentStartDate, dbo.Tasks.TaskStartTime AS ParentStartTime, 
                         dbo.Tasks.MinTimeToComplete AS ParentMinTimeToComplete, dbo.Tasks.CompleteConfirmedDate AS ParentCompleteConfirmedDate, dbo.Tasks.CompletedByServicerID AS Expr6, dbo.Tasks.Abbreviation AS expr22, 
                         Services_1.Abbreviation AS ParentServiceAbbreviation, dbo.Tasks.TaskDate AS ParentTaskDate, dbo.Tasks.TaskTime AS ParentTaskTime, Tasks_1.TaskType, Servicers_2.ShowTaskTimeEstimates, 
                         dbo.TasksToServicers.AcceptedDate, dbo.TasksToServicers.DeclinedDate, dbo.TimeZones.Region AS TimeZoneRegion, Customers_1.iCALUUID, Servicers_2.RequestAcceptTasks, Tasks_1.NotifyServicerOnNotYetDoneHours, 
                         dbo.TasksToServicers.Instructions, dbo.Properties.Active AS PropertyActive, dbo.PropertyBookings.InGlobalNote, dbo.PropertyBookings.OutGlobalNote,
                             (SELECT        TOP (1) ClockIn
                               FROM            dbo.TimeClockTasks
                               WHERE        (ServicerID = Servicers_2.ServicerID) AND (TaskID = Tasks_1.TaskID) AND (ClockOut IS NULL)
                               ORDER BY ClockIn DESC) AS ClockIn, Servicers_2.TaskName AS OnTheFlyTaskName, Customers_1.BusinessName, dbo.Customers.BusinessName AS LinkedBusinessName, dbo.PropertyBookings.OwnerNote, 
                         dbo.ServiceGroups.ServiceGroup, dbo.Customers.Active AS LinkedCustomerActive, Tasks_1.NotifyCustomerOnSupplyFlag, Tasks_1.IncludeSupplyFlag, Tasks_1.SupplyFlag, Tasks_1.TaskTimeMinutes, Tasks_1.Billable, 
                         Tasks_1.Amount, dbo.Services.Billable AS Expr2, dbo.Services.BH247CleaningState, dbo.Services.BH247QAState, dbo.Services.BH247MaintenanceState, dbo.Services.BH247Custom_1State, 
                         dbo.Services.BH247Custom_2State, dbo.Properties.BeHome247ID, dbo.PropertyItems.PropertyItemTypeID, Tasks_1.PropertyItemID, dbo.PropertyItems.Store, dbo.PropertyItems.Brand, dbo.PropertyItems.Model, 
                         dbo.PropertyItems.PartNumber, dbo.PropertyItems.SerialNumber, dbo.PropertyItems.Phone AS Expr3, dbo.PropertyItems.Warranty, dbo.PropertyItems.Description AS Expr4, dbo.PropertyItemTypes.PropertyItemType, 
                         dbo.PropertyBookings.BH247CheckedOut, Customers_1.ShowTaskEndTimeOnSchedulingCalendar, dbo.Tasks.NextPropertyBookingID, dbo.PropertyBookings.IsOwner, dbo.Services.Color AS ServiceColor, 
                         dbo.Services.OneOffVacantOnly, dbo.Properties.lat, dbo.Properties.lon, Tasks_1.TaskStartTimeMinutes, Tasks_1.TaskCompleteByTimeMinutes, Tasks_1.Urgent, Tasks_1.IncludeUrgentFlag, 
                         dbo.PropertyBookings.CheckInTimeMinutes, dbo.PropertyBookings.CheckOutTimeMinutes, dbo.Services.ScheduleFirstDay, Tasks_1.InternalNotes, Tasks_1.NotifyServicerOnCheckout, dbo.Regions.RegionGroupID, 
                         dbo.Properties.InternalNotes AS InternalPropertyNotes, Tasks_1.CreatedByServicerID, Servicers_2.Active AS ServicerActive, dbo.PropertyBookings.Active AS PropertyBookingActive, Servicers_2.TimeTracking, 
                         dbo.TasksToServicers.PiecePayStatus, dbo.TasksToServicers.ApprovedServicerID, dbo.TasksToServicers.PaidServicerID, dbo.TasksToServicers.FlaggedServicerID, Tasks_1.DeactivatedByServicerID, Tasks_1.ActiveForOwner, 
                         Tasks_1.OwnerReportNote, Tasks_1.IncludeToOwnerNoteOnOwnerDashboard, Tasks_1.TaskDescriptionImage1, Tasks_1.ShowTaskImage1OnOwnerReport, Tasks_1.TaskDescriptionImage2, 
                         Tasks_1.ShowTaskImage2OnOwnerReport, Tasks_1.TaskDescriptionImage3, Tasks_1.ShowTaskImage3OnOwnerReport, dbo.PropertyBookings.BookingTags, Tasks_1.AutosaveCount, dbo.PropertyBookings.ImportBookingID, 
                         dbo.Services.ESCAPIAHOUSEKEEPINGSTATUS, dbo.Services.ChecklistID, dbo.RegionGroups.SortOrder AS RegionGroupSortOrder, Tasks_1.BackToBack, Customers_1.Active AS CustomerActive, 
                         dbo.Services.CloudbedsHousekeepingStatus, Tasks_1.ExpenseAmount, dbo.PropertyBookings.GuestEmail, dbo.PropertyBookings.GuestPhone, dbo.PropertyBookings.ManualBookingTags, dbo.Services.OpertoStatus, 
                         Servicers_2.IncludeGuestEmailPhone, dbo.Properties.OpertoFlag, dbo.PropertyBookings.LockingSystemCheckedOut, dbo.TasksToServicers.PayType, dbo.TasksToServicers.PayRate, Servicers_2.AllowAddStandardTask, 
                         Tasks_1.SendWorkOrder, Tasks_1.WorkOrderSentDate, Tasks_1.WorkOrderIntegrationCompany, Tasks_1.WorkOrderID, Tasks_1.WorkOrderError, dbo.Services.StreamlineHousekeepingStatus, 
                         dbo.Services.StreamlineStatus
FROM            dbo.RegionGroups RIGHT OUTER JOIN
                         dbo.Regions ON dbo.RegionGroups.RegionGroupID = dbo.Regions.RegionGroupID RIGHT OUTER JOIN
                         dbo.Tasks RIGHT OUTER JOIN
                         dbo.PropertyItemTypes RIGHT OUTER JOIN
                         dbo.PropertyItems ON dbo.PropertyItemTypes.PropertyItemTypeID = dbo.PropertyItems.PropertyItemTypeID RIGHT OUTER JOIN
                         dbo.Tasks AS Tasks_1 ON dbo.PropertyItems.PropertyItemID = Tasks_1.PropertyItemID ON dbo.Tasks.TaskID = Tasks_1.ParentTaskID LEFT OUTER JOIN
                         dbo.Servicers ON Tasks_1.CompletedByServicerID = dbo.Servicers.ServicerID LEFT OUTER JOIN
                         dbo.PropertyBookings ON Tasks_1.PropertyBookingID = dbo.PropertyBookings.PropertyBookingID LEFT OUTER JOIN
                         dbo.Services LEFT OUTER JOIN
                         dbo.ServiceGroups ON dbo.Services.ServiceGroupID = dbo.ServiceGroups.ServiceGroupID LEFT OUTER JOIN
                         dbo.Services AS Services_1 ON dbo.Services.ParentServiceID = Services_1.ServiceID ON Tasks_1.ServiceID = dbo.Services.ServiceID LEFT OUTER JOIN
                         dbo.Owners RIGHT OUTER JOIN
                         dbo.Customers RIGHT OUTER JOIN
                         dbo.Properties AS Properties_1 ON dbo.Customers.CustomerID = Properties_1.CustomerID RIGHT OUTER JOIN
                         dbo.Properties ON Properties_1.PropertyID = dbo.Properties.LinkedPropertyID LEFT OUTER JOIN
                         dbo.Servicers AS Servicers_1 ON dbo.Properties.CustomerID = Servicers_1.LinkedCustomerID LEFT OUTER JOIN
                         dbo.Customers AS Customers_1 ON dbo.Properties.CustomerID = Customers_1.CustomerID ON dbo.Owners.OwnerID = dbo.Properties.OwnerID ON Tasks_1.PropertyID = dbo.Properties.PropertyID ON 
                         dbo.Regions.RegionID = dbo.Properties.RegionID LEFT OUTER JOIN
                         dbo.TasksToServicers ON Tasks_1.TaskID = dbo.TasksToServicers.TaskID LEFT OUTER JOIN
                         dbo.Servicers AS Servicers_2 ON dbo.TasksToServicers.ServicerID = Servicers_2.ServicerID LEFT OUTER JOIN
                         dbo.TimeZones ON Customers_1.TimeZoneID = dbo.TimeZones.TimeZoneID
WHERE        (dbo.PropertyBookings.DeletedDate IS NULL)
';
}