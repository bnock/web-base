<?php
namespace App\Contracts;

use ArrayAccess;
use Illuminate\Contracts\Queue\QueueableEntity;
use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

interface ApiResource extends ArrayAccess, Arrayable, Jsonable, JsonSerializable, QueueableEntity, UrlRoutable
{
    /**
     * Get the Resource class for building responses.
     *
     * @return string
     */
    public static function getResourceClass(): string;

    /**
     * Get the resource key.
     *
     * @return string
     */
    public static function getResourceKey(): string;

    /**
     * Get the required role for the ALL operation. Optionally, return a callable for granular control.
     *
     * @return string|callable
     */
    public static function getAllRoleOrGate();

    /**
     * Get the required role for the ONE operation. Optionally, return a callable for granular control.
     *
     * @return string|callable
     */
    public static function getOneRoleOrGate();

    /**
     * Get the required role for the CREATE operation. Optionally, return a callable for granular control.
     *
     * @return string|callable
     */
    public static function getCreateRoleOrGate();

    /**
     * Get the required role for the UPDATE operation. Optionally, return a callable for granular control.
     *
     * @return string|callable
     */
    public static function getUpdateRoleOrGate();

    /**
     * Get the required role for the DELETE operation. Optionally, return a callable for granular control.
     *
     * @return string|callable
     */
    public static function getDeleteRoleOrGate();
}
