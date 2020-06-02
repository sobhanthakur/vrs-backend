<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 20/4/20
 * Time: 7:58 PM
 */

namespace AppBundle\DatabaseViews;


final class Issues
{
    const vIssues = 'SELECT Issues.FromTaskID, Issues.IssueType, Issues.Issue, Issues.PropertyID, Issues.Notes, Issues.TaskID, Issues.Image1, Issues.Image2, Issues.Image3, Issues.ClosedDate, 
                         Issues.CreateDate, Issues.IssueID, Properties.PropertyName, Properties.CustomerID, Customers.CustomerName, Tasks_1.TaskName AS FromTaskName, Services_1.ServiceName AS FromServiceName, 
                         Servicers_1.Name AS FromServicerName, Servicers_1.ServicerID AS FromServicerID, Regions.Region, Properties.RegionID, Properties.OwnerID, Owners.OwnerName, Servicers_1.LinkedCustomerID, 
                         Properties.LinkedPropertyID, Customers.GoLiveDate, Issues.ServicerNotes, Servicers.Name AS SubmittedByName, Issues.SubmittedByServicerID, Customers.BusinessName, 
                         Owners.OwnerEmail, PropertyItemTypes.PropertyItemTypeID, PropertyItemTypes.PropertyItemType, PropertyItemTypes.SortOrder, PropertyItems.PropertyItemID, PropertyItems.Store, 
                         PropertyItems.Brand, PropertyItems.Model, PropertyItems.PartNumber, PropertyItems.SerialNumber, PropertyItems.Phone, PropertyItems.Warranty, PropertyItems.Description, Issues.Billable, 
                         Issues.Amount, Tasks_1.CompleteConfirmedDate, Issues.Urgent, Regions.TimeZoneID, TimeZones.Region AS TimeZoneRegion, Issues.StatusID, Properties.PropertyAbbreviation, 
                         Properties.Address, Issues.ShowOnOwnerDashboard, Issues.ShowOwnerImage1, Issues.ShowOwnerImage2, Issues.ShowOwnerImage3, Issues.InternalNotes, Issues.ShowOnVendorDashboard, 
                         Issues.ShowVendorImage1, Issues.ShowVendorImage2, Issues.ShowVendorImage3, Services_1.ServiceID AS FromServiceID, Servicers.Email AS FromServicerEmail, 
                         Servicers.Phone AS FromServicerPhone FROM TasksToServicers LEFT OUTER JOIN
                         Servicers AS Servicers_1 ON TasksToServicers.ServicerID = Servicers_1.ServicerID RIGHT OUTER JOIN
                         Tasks AS Tasks_1 ON TasksToServicers.TaskID = Tasks_1.TaskID RIGHT OUTER JOIN
                         Servicers RIGHT OUTER JOIN
                         PropertyItems RIGHT OUTER JOIN
                         Issues ON PropertyItems.PropertyItemID = Issues.PropertyItemID LEFT OUTER JOIN
                         PropertyItemTypes ON PropertyItems.PropertyItemTypeID = PropertyItemTypes.PropertyItemTypeID ON Servicers.ServicerID = Issues.SubmittedByServicerID ON 
                         Tasks_1.TaskID = Issues.FromTaskID LEFT OUTER JOIN
                         Regions LEFT OUTER JOIN
                         TimeZones ON Regions.TimeZoneID = TimeZones.TimeZoneID RIGHT OUTER JOIN
                         Owners RIGHT OUTER JOIN
                         Properties ON Owners.OwnerID = Properties.OwnerID ON Regions.RegionID = Properties.RegionID LEFT OUTER JOIN
                         Customers ON Properties.CustomerID = Customers.CustomerID ON Issues.PropertyID = Properties.PropertyID LEFT OUTER JOIN
                         Services AS Services_1 ON Tasks_1.ServiceID = Services_1.ServiceID
                         WHERE (TasksToServicers.IsLead = 1) OR (TasksToServicers.IsLead IS NULL)';
}