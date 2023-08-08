<?php

namespace App\Models\Access\User\Traits\Attribute;

/**
 * Class UserAttribute.
 */
trait UserAttribute
{
    /**
     * @return mixed
     */
    public function canChangeEmail()
    {
        return config('access.users.change_email');
    }

    /**
     * @return bool
     */
    public function canChangePassword()
    {
        return ! app('session')->has(config('access.socialite_session_name'));
    }

    /**
     * @return string
     */
    public function getStatusLabelAttribute()
    {
        if ($this->isActive()) {
            return "<label class='label label-success'>".trans('labels.general.active').'</label>';
        }

        return "<label class='label label-danger'>".trans('labels.general.inactive').'</label>';
    }

    /**
     * @return string
     */
    public function getConfirmedLabelAttribute()
    {
        if ($this->isConfirmed()) {
            return "<label class='label label-success'>".trans('labels.general.yes').'</label>';
        }

        return "<label class='label label-danger'>".trans('labels.general.no').'</label>';
    }

    /**
     * @return mixed
     */
    public function getPictureAttribute()
    {
        return $this->getPicture();
    }

    /**
     * @param bool $size
     *
     * @return mixed
     */
    public function getPicture($size = false)
    {
        if (! $size) {
            $size = config('gravatar.default.size');
        }

        if($this->image) {
            return $this->image;
        } else {
            return gravatar()->get($this->email, ['size' => $size]);
        }

        
    }

    /**
     * @param $provider
     *
     * @return bool
     */
    public function hasProvider($provider)
    {
        foreach ($this->providers as $p) {
            if ($p->provider == $provider) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->status == 1;
    }

    /**
     * @return bool
     */
    public function isConfirmed()
    {
        return $this->confirmed == 1;
    }


    public function adminRoles()
    {
      return $this->roles()->where('roles.all', 1);  
    } 

    /**
     * [isAdmin description]
     * @version [version]
     * @author lutzjw
     * @date:   2018-05-02T13:20:53-0500
     * @since   [version]
     * @return  boolean                  [description]
     */
    public function isAdmin()
    {
        return count($this->roles()->where('all',1)) > 0;
    } 
    /**
     * @return string
     */
    public function getShowButtonAttribute()
    {
        return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.crud.view'). '">
        <a href="'.route('admin.access.user.show', $this).'" class="btn-sm btn-info"><i class="fa fa-search fa-fw"></i></a></span> ';
    }

    /**
     * @return string
     */
    public function getEditButtonAttribute()
    {
         //Only show the button if this is not a SimMedical Administrator (role.all == 1)
        if(count($this->adminRoles) == 0)
        {
            return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.general.crud.edit'). '">
            <a href="'.route('admin.access.user.edit', $this).'" class="btn-sm btn-primary"><i class="fa fa-pencil-alt fa-fw"></i></a></span> ';
        }   
    }

    /**
     * @return string
     */
    public function getChangePasswordButtonAttribute()
    {
        //Only show the button if this is not a SimMedical Administrator (role.all == 1)
        if(count($this->adminRoles) == 0)
        // if (! $this->isAdmin())
        {
            return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.backend.access.users.change_password'). '">
            <a href="'.route('admin.access.user.change-password', $this).'" class="btn-sm btn-secondary">
            <i class="fa fa-key fa-fw"></i></a></span> ';
        }
    }

    /**
     * @return string
     */
    public function getStatusButtonAttribute()
    {
        if (($this->id != access()->id()) && (count($this->adminRoles) == 0) ) {
            switch ($this->status) {
                case 0:
                    return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.backend.access.users.activate'). '">
                    <a href="'.route('admin.access.user.mark', [
                        $this,
                        1,
                    ]).'" class="btn-sm btn-success"><i class="fa fa-play fa-fw"></i></a></span> ';
                // No break

                case 1:
                    return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.backend.access.users.deactivate'). '">
                    <a href="'.route('admin.access.user.mark', [
                        $this,
                        0,
                    ]).'" class="btn-sm btn-warning">
                    <i class="fa fa-pause fa-fw text-white"></i></a></span> ';
                // No break

                default:
                    return '';
                // No break
            }
        }

        return '';
    }

    /**
     * @return string
     */
    public function getConfirmedButtonAttribute()
    {
        if (! $this->isConfirmed()) {
            return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.backend.access.users.resend_email'). '">
            <a href="'.route('admin.access.user.account.confirm.resend', $this).'" class="btn-sm btn-success">
            <i class="fa fa-sync-alt fa-fw"></i></a></span> ';
        }

        return '';
    }

    /**
     * @return string
     */
    public function getDeleteButtonAttribute()
    {
        if ($this->id != access()->id() && $this->id != 1) {
            return '<a href="'.route('admin.access.user.destroy', $this).'"
                 data-method="delete"
                 data-trans-button-cancel="'.trans('buttons.general.cancel').'"
                 data-trans-button-confirm="'.trans('buttons.general.crud.delete').'"
                 data-trans-title="'.trans('strings.backend.general.are_you_sure').'"
                 class="btn-sm btn-danger"><i class="fa fa-trash fa-fw" data-toggle="tooltip" data-placement="top" title="'.trans('buttons.general.crud.delete').'"></i></a> ';
        }
        return '';
    }

    /**
     * @return string
     */
    public function getRestoreButtonAttribute()
    {
        return '<a href="'.route('admin.access.user.restore', $this).'" name="restore_user" class="btn-sm btn-info"><i class="fa fa-sync-alt fa-fw" data-toggle="tooltip" data-placement="top" title="'.trans('buttons.backend.access.users.restore_user').'"></i></a> ';
    }

    /**
     * @return string
     */
    public function getDeletePermanentlyButtonAttribute()
    {
        return '<a href="'.route('admin.access.user.delete-permanently', $this).'" name="delete_user_perm" class="btn-sm btn-danger"><i class="fa fa-trash fa-fw" data-toggle="tooltip" data-placement="top" title="'.trans('buttons.backend.access.users.delete_permanently').'"></i></a> ';
    }

    /**
     * @return string
     */
    public function getProfileQuestionAnswersEditButtonAttribute()
    {
        return '<a href="'.route('admin.access.user.question-answers', $this).'" name="delete_user_perm" class="btn btn-sm btn-success"><i class="fa fa-user" data-toggle="tooltip" data-placement="top" title="'.trans('buttons.backend.access.users.edit_profile').'"></i></a> ';
    }
    
    /**
     * @return string
     */
    public function getLoginAsButtonAttribute()
    {
        //Only show the button if this is not a SimMedical Administrator (role.all == 1)
        if(count($this->adminRoles) == 0)
        {
            /*
             * If the admin is currently NOT spoofing a user
             */
            if (! session()->has('admin_user_id') || ! session()->has('temp_user_id')) {
                if (  ($this->id != access()->id())             //Can't "Login As" themselves
                    && access()->hasPermission('manage-users')  //but need to have 'manage-users' permission
                    ) {
                    return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.backend.access.users.login_as', ['user' => $this->full_name]) .'">
                    <a href="'.route('admin.access.user.login-as',
                        $this).'" class="btn-sm btn-success"><i class="fa fa-user fa-fw"></i></a></span> ';
                }
            }
        }
        return '';
    }

    /**
     * @return string
     */
    public function getClearSessionButtonAttribute()
    {
        if ($this->id != access()->id() && config('session.driver') == 'database') {
            return '<span class="simptip-position-top simptip-smooth" data-tooltip="' . trans('buttons.backend.access.users.clear_session'). '">
            <a href="'.route('admin.access.user.clear-session', $this).'"
			 	 data-trans-button-cancel="'.trans('buttons.general.cancel').'"
                 data-trans-button-confirm="'.trans('buttons.general.continue').'"
                 data-trans-title="'.trans('strings.backend.general.are_you_sure').'"
                 class="btn-sm btn-danger" name="confirm_item"><i class="fa fa-times fa-fw"></i></a></span> ';
        }

        return '';
    }

    /**
     * @return string
     */
    public function getActionButtonsAttribute()
    {
        if ($this->trashed()) {
            return $this->getRestoreButtonAttribute().
                $this->getDeletePermanentlyButtonAttribute();
        }

        return
            // $this->getProfileQuestionAnswersEditButtonAttribute().  // I hid this until we are ready to implement this -jl 2019-07-17 14:33
            $this->getShowButtonAttribute().
            $this->getEditButtonAttribute().
            $this->getChangePasswordButtonAttribute().
            $this->getLoginAsButtonAttribute().
            $this->getClearSessionButtonAttribute().
            $this->getStatusButtonAttribute().
            $this->getConfirmedButtonAttribute();
            // We removed the delete button so that users cannot permanently delete users
            // -jl 2018-03-29 9:22 
            //$this->getDeleteButtonAttribute();
    }

    /**
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->last_name
             ? $this->first_name.' '.$this->last_name
             : $this->first_name;
    }

    /**
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->full_name;
    }

    /**
     * @return string
     */
    public function getNameEmailAttribute()
    {
        return $this->full_name . ' (<a href="mailto:' . $this->email .'">' . $this->email . '</a>)';
    }

}
