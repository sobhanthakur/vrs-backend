<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 5/5/20
 * Time: 6:17 PM
 */

namespace AppBundle\DatabaseViews;


final class ServicesToProperties
{
    const vServicesToProperties = 'SELECT        dbo.ServicesToProperties.ServiceID, dbo.Properties.PropertyName, dbo.ServicesToProperties.ServiceToPropertyID, dbo.ServicesToProperties.PropertyID, dbo.Services.ServiceName, dbo.Properties.CustomerID, 
                         dbo.Services.AddPerBooking, dbo.Services.TaskType, dbo.ServicesToProperties.DefaultServicerID, dbo.ServicesToProperties.MinTimeToComplete, dbo.ServicesToProperties.MaxTimeToComplete, 
                         dbo.ServicesToProperties.NumberOfServicers, dbo.ServicesToProperties.PiecePay, dbo.Servicers.ServicerID, dbo.Servicers.WorkDays, dbo.Servicers.BackupServicerID1, dbo.Servicers.BackupServicerID2, 
                         dbo.Servicers.BackupServicerID3, dbo.Servicers.BackupServicerID4, dbo.Servicers.BackupServicerID5, dbo.Servicers.BackupServicerID6, dbo.Servicers.BackupServicerID7, dbo.Services.NotifyCustomerOnCompletion, 
                         dbo.Services.NotifyCustomerOnOverdue, dbo.Services.NotifyCustomerOnDamage, dbo.Services.NotifyCustomerOnMaintenance, dbo.Services.NotifyCustomerOnLostAndFound, dbo.Services.NotifyCustomerOnServicerNote, 
                         dbo.Services.IncludeDamage, dbo.Services.IncludeMaintenance, dbo.Services.IncludeLostAndFound, dbo.Services.IncludeServicerNote, dbo.Services.NotifyServicerOnOverdue, dbo.Services.NotifyCustomerOnNotYetDone, 
                         dbo.Services.NotifyServicerOnNotYetDone, dbo.Services.NotifyOnAssignment, dbo.Services.NotifyOwnerOnCompletion, dbo.Services.NotifyServicerOnNotYetDoneHours, dbo.Services.IncludeImageUpload, 
                         dbo.Services.AllowShareImagesWithOwners, dbo.Services.DefaultToOwnerNote, dbo.Services.IncludeToOwnerNote, dbo.Properties.SortOrder, dbo.Regions.SortOrder AS RegionSortOrder, dbo.Properties.Active, 
                         dbo.Services.IncludeSupplyFlag, dbo.Services.OneOffVacantOnly, dbo.Services.NoDefaultServicerAssignedWithinDays, dbo.Services.NotifyCustomerOnSupplyFlag, dbo.Services.Billable, dbo.Services.BH247CleaningState, 
                         dbo.Services.BH247QAState, dbo.Services.BH247MaintenanceState, dbo.Services.BH247Custom_1State, dbo.Services.BH247Custom_2State, dbo.Servicers.Name, dbo.Services.Active AS ServiceActive, 
                         dbo.Services.Abbreviation, dbo.Properties.PropertyAbbreviation, dbo.Services.ServiceGroupID, dbo.Regions.Region, dbo.Regions.RegionID, dbo.Services.ActiveForOwner, dbo.ServicesToProperties.ChecklistID, 
                         dbo.Services.Amount, dbo.Services.ExpenseAmount, dbo.Services.IncludeOnIssueForm, dbo.Services.PayType, dbo.Servicers.PayRate, dbo.ServicesToProperties.LaborAmount, dbo.ServicesToProperties.MaterialsAmount, 
                         dbo.ServicesToProperties.LinenCounts, dbo.Services.PackLinen, dbo.Services.RetrieveLinen
FROM            dbo.Properties INNER JOIN
                         dbo.ServicesToProperties ON dbo.Properties.PropertyID = dbo.ServicesToProperties.PropertyID INNER JOIN
                         dbo.Services ON dbo.ServicesToProperties.ServiceID = dbo.Services.ServiceID LEFT OUTER JOIN
                         dbo.Regions ON dbo.Properties.RegionID = dbo.Regions.RegionID LEFT OUTER JOIN
                         dbo.Servicers ON dbo.ServicesToProperties.DefaultServicerID = dbo.Servicers.ServicerID';
}