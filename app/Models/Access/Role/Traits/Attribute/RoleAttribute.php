<?php

namespace App\Models\Access\Role\Traits\Attribute;

/**
 * Class RoleAttribute.
 */
trait RoleAttribute
{
    /**
     * @return string
     */
    public function getEditButtonAttribute()
    {
        return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.crud.edit'). '">
        <a href="'.route('admin.access.role.edit', $this).'" class="btn-sm btn-primary">
        <i class="fa fa-pencil-alt fa-fw"></i></a></span> ';
    }

    /**
     * @return string
     */
    public function getDeleteButtonAttribute()
    {
        //Can't delete master admin role
        if ($this->id != 1) {
            return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.crud.delete'). '">
                <a href="'.route('admin.access.role.destroy', $this).'"
                data-method="delete"
                data-trans-button-cancel="'.trans('buttons.general.cancel').'"
                data-trans-button-confirm="'.trans('buttons.general.crud.delete').'"
                data-trans-title="'.trans('alerts.backend.roles.delete_wall').'"
                data-trans-text="'.$this->name.'"
                class="btn-sm btn-danger"><i class="fa fa-trash fa-fw"></i></a></span> ';
        }

        return '';
    }

    public function getSiteUsersButtonAttribute()
    {
        return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('menus.backend.site.assign'). '">
        <a href="/site/users/' . $this->id . '"
        class="btn-sm btn-users"><i class="fas fa-fw fa-user"></i></a></span> ';
    }

    /**
     * @return string
     */
    public function getActionButtonsAttribute()
    {
        return $this->getEditButtonAttribute().
        $this->getDeleteButtonAttribute();
    }

    public function getSiteButtonsAttribute()
    {
        return $this->getEditButtonAttribute().
        $this->getSiteUsersButtonAttribute().
        $this->getDeleteButtonAttribute();
    }
}
