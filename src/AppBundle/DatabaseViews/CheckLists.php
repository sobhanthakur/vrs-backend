<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 21/4/20
 * Time: 3:55 PM
 */

namespace AppBundle\DatabaseViews;


final class CheckLists
{
    const vServicesToPropertiesChecklistItems = 'SELECT dbo.Checklists.ChecklistName, dbo.ChecklistsToChecklistItems.ChecklistItemID, dbo.ChecklistItems.ChecklistTypeID, dbo.ChecklistItems.ChecklistItem, dbo.ChecklistItems.InternalName, dbo.ChecklistItems.Description, 
                         dbo.ChecklistItems.Image, dbo.ChecklistItems.Options, dbo.ChecklistItems.ColumnCount, dbo.ChecklistItems.Required, dbo.ChecklistItems.ShowOnOwnerReport, dbo.ChecklistItems.ShowImageOnOwnerReport, 
                         dbo.ChecklistItems.ChecklistItemID AS Expr1, dbo.Checklists.ParentChecklistID, dbo.Checklists.ChecklistID, dbo.ServicesToProperties.ChecklistID AS Expr2, dbo.ServicesToProperties.ServiceID, 
                         dbo.ServicesToProperties.PropertyID, dbo.ServicesToProperties.ServiceToPropertyID, dbo.ChecklistsToChecklistItems.SortOrder, dbo.ChecklistItems.ShowDescription
FROM            dbo.ChecklistItems RIGHT OUTER JOIN
                         dbo.Checklists RIGHT OUTER JOIN
                         dbo.ServicesToProperties ON dbo.Checklists.ChecklistID = dbo.ServicesToProperties.ChecklistID LEFT OUTER JOIN
                         dbo.ChecklistsToChecklistItems ON dbo.Checklists.ChecklistID = dbo.ChecklistsToChecklistItems.ChecklistID ON dbo.ChecklistItems.ChecklistItemID = dbo.ChecklistsToChecklistItems.ChecklistItemID';

    const vServicesChecklistItems = 'SELECT        dbo.Checklists.ChecklistName, dbo.ChecklistsToChecklistItems.ChecklistItemID, dbo.ChecklistItems.ChecklistTypeID, dbo.ChecklistItems.ChecklistItem, dbo.ChecklistItems.InternalName, dbo.ChecklistItems.Description, 
                         dbo.ChecklistItems.Image, dbo.ChecklistItems.Options, dbo.ChecklistItems.ColumnCount, dbo.ChecklistItems.Required, dbo.ChecklistItems.ShowOnOwnerReport, dbo.ChecklistItems.ShowImageOnOwnerReport, 
                         dbo.ChecklistItems.ChecklistItemID AS Expr1, dbo.Checklists.ParentChecklistID, dbo.Checklists.ChecklistID, dbo.Services.ServiceID, dbo.ChecklistsToChecklistItems.SortOrder, dbo.ChecklistItems.ShowDescription
FROM            dbo.Checklists RIGHT OUTER JOIN
                         dbo.Services ON dbo.Checklists.ChecklistID = dbo.Services.ChecklistID LEFT OUTER JOIN
                         dbo.ChecklistsToChecklistItems ON dbo.Checklists.ChecklistID = dbo.ChecklistsToChecklistItems.ChecklistID LEFT OUTER JOIN
                         dbo.ChecklistItems ON dbo.ChecklistsToChecklistItems.ChecklistItemID = dbo.ChecklistItems.ChecklistItemID';
}