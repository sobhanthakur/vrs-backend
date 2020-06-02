<?php
/**
 * Created by PhpStorm.
 * User: sobhan
 * Date: 12/5/20
 * Time: 7:30 PM
 */

namespace AppBundle\DatabaseViews;


final class AdditionalDefaultServicers
{
    const vAdditionalDefaultServicers = 'SELECT        dbo.AdditionalDefaultServicers.ServicerID, dbo.AdditionalDefaultServicers.ServiceToPropertyID, dbo.vServicers.BackupServicerID1, dbo.vServicers.BackupServicerID2, dbo.vServicers.BackupServicerID3, 
                         dbo.vServicers.BackupServicerID4, dbo.vServicers.BackupServicerID5, dbo.vServicers.BackupServicerID6, dbo.vServicers.BackupServicerID7, dbo.vServicers.WorkDays, dbo.AdditionalDefaultServicers.PiecePay, 
                         dbo.AdditionalDefaultServicers.AdditionalDefaultServicerID, dbo.vServicers.PayRate
FROM            dbo.AdditionalDefaultServicers LEFT OUTER JOIN
                         dbo.vServicers ON dbo.AdditionalDefaultServicers.ServicerID = dbo.vServicers.ServicerID';
}