<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 5/5/20
 * Time: 1:22 PM
 */

namespace AppBundle\DatabaseViews;


class Tasks
{
    final function TasksQuery($servicerID,$customerID)
    {
        return 'SELECT
      Distinct TOP 61 TasksToServicers.IsLead,Tasks.PropertyID,Tasks.ServiceID,Tasks.TaskCompleteByDate,Tasks.TaskCompleteByTime,Tasks.TaskDAte,Tasks.TaskTime,Tasks.TaskTimeMinutes,PropertyBookings.Color as BookingColor,Services.Color as ServiceColor,Regions.Color,Properties.PropertyFile,Properties.Description,Properties.Address,Tasks.TaskStartDate,Tasks.TaskID,TasksToServicers.AcceptedDate,Regions.Region,PropertyBookings.BackToBackStart,PropertyBookings.BackToBackEnd,Services.TaskType,Properties.PropertyName,Servicers.IncludeGuestName,IncludeGuestEmailPhone,PropertyBookings.Guest,PropertyBookings.GuestEmail,PropertyBookings.GuestPhone,PropertyBookings.IsOwner,SErvicers.IncludeGuestNumbers,Services.ShowAllTagsOnDashboards,Services.ShowPMSHousekeepingNoteOnDashboards,
      PropertyBookings.NumberOfGuests,PropertyBookings.NumberOfChildren,PropertyBookings.NumberOfPets,Services.ServiceName,Tasks.TaskName,Tasks.ParentTaskID,ParentTask.CompleteConfirmedDate as ParentCompleteConfirmedDAte,ParentServices.Abbreviation as ParentServiceAbbreviation,Tasks.TaskDateTime,Tasks.TaskStartTime,Servicers.AllowChangeTaskDate,TasksToServicers.Instructions,PropertyBookings.InGlobalNote,PropertyBookings.OutGlobalNote,PropertyBookings.OwnerNote,Tasks.TaskDescription,TAsks.InternalNotes,Tasks.IncludeToOwnerNote,Tasks.DefaultToOwnerNote,Tasks.IncludeServicerNote,Tasks.IncludeMaintenance,Tasks.IncludeDamage,Tasks.IncludeLostAndFound,Tasks.IncludeSupplyFlag,Tasks.AllowShareImagesWithOwners,Properties.Doorcode,Propertybookings.PropertyID as PropertyBookingPropertyID,
      Tasks.MINTIMETOCOMPLETE,Tasks.MAXTIMETOCOMPLETE,PropertyBookings.GLOBALNOTE,ParentTask.TaskDate as PARENTTASKDATE,TasksToServicers.piecepay,PropertyBookings.BookingTags,PropertyBookings.ManualBookingTags,NextPropertyBooking.BookingTags as NextBookingTags,NextPropertyBooking.ManualBookingTags as NextManualBookingTags,
      Regions.SortOrder as RegionSortOrder,RegionGroups.SortOrder as RegionGroupSortOrder,Properties.SortOrder as PropertySortOrder,Lat,Lon,TImeZones.Region as TimeZoneRegion,Tasks.IncludeUrgentFlag,Tasks.TASKCOMPLETEBYTIMEMINUTES,Tasks.TASKSTARTTIMEMINUTES,Properties.InternalNotes as InternalPropertyNotes,Services.ShortDescription,Tasks.PropertyBOokingID,Tasks.NExtPropertyBOokingID,Tasks.TaskDescriptionImage1,Tasks.TaskDescriptionImage2,Tasks.TaskDescriptionImage3,Tasks.ServicerNotes,Tasks.AutosaveCount,Tasks.BackToBack,TAsks.ToOwnerNote,Servicers.AllowAddStandardTask,NextPropertyBooking.NumberOfGuests as NextNumberOfGuests,NextPropertyBooking.NumberOfChildren as NextNumberOfChildren,NextPropertyBooking.NumberOfPets as NextNumberOfPets,NextPropertyBooking.ImportBookingID as NextImportBookingID,NextPropertyBooking.PMSHousekeepingNote as NextPMSHousekeepingNote,PropertyBookings.ImportBookingID as ImportBookingID,PropertyBookings.PMSHousekeepingNote as PMSHousekeepingNote,
      PropertyBookings.CheckIn,PropertyBookings.CheckInTime,PropertyBookings.CheckInTimeMinutes,
      PropertyBookings.CheckOut,PropertyBookings.CheckOutTime,PropertyBookings.CheckOutTimeMinutes,
      NextPropertyBooking.CheckIn as NextCheckIn,NextPropertyBooking.CheckInTime as NextCheckInTime,NextPropertyBooking.CheckInTimeMinutes as NextCheckInTimeMinutes,
      NextPropertyBooking.CheckOut as NextCheckOut,NextPropertyBooking.CheckOutTime as NextCheckOutTime,NextPropertyBooking.CheckOutTimeMinutes as NextCheckOutTimeMinutes,
      PropertyBookings.LinenCounts,
      NextPropertyBooking.LinenCounts as NextLinenCounts,
      NextPropertyBooking.IsOwner as NextIsOwner,
      NextPropertyBooking.BackToBackStart as NextBackToBackStart,
      NextPropertyBooking.BackToBackEnd as NextBackToBackEnd,
      NextPropertyBooking.Guest as NextGuest,
      NextPropertyBooking.GuestEmail as NextGuestEmail,
      NextPropertyBooking.GuestPhone as NextGuestPhone,
      NextPropertyBooking.GlobalNote as NextGlobalNote,
      NextPropertyBooking.InGlobalNote as NextInGlobalNote,
      NextPropertyBooking.OutGlobalNote as NextOutGlobalNote,
      NextPropertyBooking.InternalNote as NextInternalNote,
      NextPropertyBooking.OwnerNote as NextOwnerNote,
      Customers.LinenFields


      FROM Tasks 
      LEFT JOIN Services ON  Tasks.ServiceID = Services.ServiceID
      LEFT JOIN PropertyBookings ON Tasks.PropertyBookingID = PropertyBookings.PropertyBookingID
      LEFT JOIN Properties ON Tasks.PropertyID = Properties.PropertyID
      LEFT JOIN Customers ON Properties.CustomerID = Customers.CustomerID
      LEFT JOIN Regions ON Properties.RegionID = Regions.RegionID
      LEFT JOIN TimeZones ON Regions.TimeZoneID = TimeZones.TimeZoneID
      Left Join RegionGroups ON Regions.RegionGroupID = RegionGroups.RegionGroupID
      LEFT JOIN TasksToServicers ON Tasks.TaskID = TasksToServicers.TaskID
      LEFT JOIN Servicers ON TasksToServicers.ServicerID = Servicers.ServicerID
      LEFT JOIN TAsks as ParentTAsk on Tasks.ParentTaskID = ParentTAsk.TaskID
      LEFT JOIN Services as ParentServices on ParentTAsk.ServiceID = ParentServices.ServiceID
      LEFT JOIN PropertyBookings NextPropertyBooking ON Tasks.NextPropertyBOokingID = NextPropertyBooking.PropertyBookingID

      WHERE Tasks.Active = 1
      AND Properties.Active = 1
      AND (Services.Active = 1 OR Services.Active IS NULL)
      AND Tasks.CompleteConfirmedDAte is NULL
      AND (Tasks.TaskDate >= Customers.GoLiveDAte or Customers.GoLiveDate is null)
      AND Servicers.ServicerID=' . $servicerID.' AND Properties.CustomerID='.$customerID;
    }
}