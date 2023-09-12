<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Models\Action;
use App\Domain\Models\AffectedUser;
use App\Domain\Models\Log;
use App\Domain\Models\User;
use App\Domain\Repositories\LogRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentLogRepository implements LogRepositoryInterface
{

    public function getAllAction(): Collection
    {
        return Action::all();
    }

    // get id and full name for affected users
    public function getAllAffectedUser(): LengthAwarePaginator
    {
        $affected_user = AffectedUser::query()->select('user_id')->distinct();

        //search by affected user's name
        if (request()->has('name')) {
            $name = request()->query('name');

            // trim the name
            $name = trim($name);

            // make the name lower case
            $name = strtolower($name);

            // access the empData table that is related to the job application table
            // and compare the first name and last name with the given name
            // and return the result
            $affected_user->whereHas('user', function ($query) use ($name) {
                $query->whereHas('employee', function ($query) use ($name) {
                    $query->whereHas('jobApplication', function ($query) use ($name) {
                        $query->whereHas('empData', function ($query) use ($name) {

                            // search after ignoring the case
                            $query->whereRaw('LOWER(first_name) LIKE ?', ["%$name%"])
                                ->orWhereRaw('LOWER(last_name) LIKE ?', ["%$name%"])
                                ->orWhereRaw('CONCAT(LOWER(first_name), " ", LOWER(last_name)) LIKE ?', ["%$name%"]);

                        });
                    });
                });
            });

        }

        return $affected_user->paginate(10);
    }


//get id and full name for users
    public function getAllUser(): LengthAwarePaginator
    {
        //NOTE : pluck is used to retrieve an array of unique user_id values
        $actioned_users = Log::query()->select('user_id')->distinct()->pluck('user_id');

        $user = User::query()->whereIn('user_id', $actioned_users);


        if (request()->has('name')) {

            $name = request()->query('name');

            // trim the name
            $name = trim($name);

            // make the name lower case
            $name = strtolower($name);

            $user->whereHas('employee', function ($query) use ($name) {
                $query->whereHas('jobApplication', function ($query) use ($name) {
                    $query->whereHas('empData', function ($query) use ($name) {

                        // search after ignoring the case
                        $query->whereRaw('LOWER(first_name) LIKE ?', ["%$name%"])
                            ->orWhereRaw('LOWER(last_name) LIKE ?', ["%$name%"])
                            ->orWhereRaw('CONCAT(LOWER(first_name), " ", LOWER(last_name)) LIKE ?', ["%$name%"]);

                    });
                });
            });

        }
        return $user->paginate(10);
    }

    public function getLog(): LengthAwarePaginator
    {
        $log = Log::query();

        // check if the request has filter by affected user
        if (request()->has('affected_user')) {
            $affectedUsersIds = request()->query('affected_user');

            // extract the comma separated values
            $affectedUsersIds = explode(',', $affectedUsersIds);

            // convert it to array of integers
            $affectedUsersIds = array_map('intval', $affectedUsersIds);

            // filter the query by the extracted ids
            $log->whereHas('affectedUser', function (Builder $query) use ($affectedUsersIds) {
                $query->whereIn('user_id', $affectedUsersIds);
            });
        }


        // check if the request has filter by actioned user
        if (request()->has('actioned_user')) {
            $actionedUsersIds = request()->query('actioned_user');

            // extract the comma separated values
            $actionedUsersIds = explode(',', $actionedUsersIds);

            // convert it to array of integers
            $actionedUsersIds = array_map('intval', $actionedUsersIds);

            // filter the query by the extracted ids
            $log->whereIn('user_id', $actionedUsersIds);

        }

        // check if the request has filter by action ids
        if (request()->has('action')) {
            $actionIds = request()->query('action');

            // extract the comma separated values
            $actionIds = explode(',', $actionIds);

            // convert it to array of integers
            $actionIds = array_map('intval', $actionIds);

            // filter the query by the extracted ids
            $log->whereIn('action_id', $actionIds);
        }

        // check if the request has filter by action severities
        if (request()->has('action_severity')) {
            $actionSeverity = request()->query('action_severity');

            // extract the comma separated values
            $actionSeverity = explode(',', $actionSeverity);

            // convert it to array of integers
            $actionSeverity = array_map('intval', $actionSeverity);

            // filter the query by the extracted ids
            $log->whereHas('action', function (Builder $query) use ($actionSeverity) {
                $query->whereIn('severity', $actionSeverity);
            });
        }

        // check if the request has filter by date range
        if (request()->has('start_date') & request()->has('end_date')) {
            $startDate = request()->query('start_date');
            $endDate = request()->query('end_date');

            $log->whereBetween('date', [$startDate, $endDate]);
        } else if (request()->has('start_date')) {
            $startDate = request()->query('start_date');

            $log->whereDate('date', '>=', $startDate);
        } else if (request()->has('end_date')) {
            $endDate = request()->query('end_date');

            $log->whereDate('date', '<=', $endDate);
        }

        //search by affected user's name
        if (request()->has('affected_user_name')) {
            $name = request()->query('affected_user_name');

            // trim the name
            $name = trim($name);

            // make the name lower case
            $name = strtolower($name);

            $log->whereHas('affectedUser', function ($query) use ($name) {
                $query->whereHas('user', function ($query) use ($name) {
                    $query->whereHas('employee', function ($query) use ($name) {
                        $query->whereHas('jobApplication', function ($query) use ($name) {
                            $query->whereHas('empData', function ($query) use ($name) {

                                // search after ignoring the case
                                $query->whereRaw('LOWER(first_name) LIKE ?', ["%$name%"])
                                    ->orWhereRaw('LOWER(last_name) LIKE ?', ["%$name%"])
                                    ->orWhereRaw('CONCAT(LOWER(first_name), " ", LOWER(last_name)) LIKE ?', ["%$name%"]);

                            });
                        });
                    });
                });
            });

        }

        //search by actioned user's name
        if (request()->has('actioned_user_name')) {
            $name = request()->query('actioned_user_name');

            // trim the name
            $name = trim($name);

            // make the name lower case
            $name = strtolower($name);

            $log->whereHas('user', function ($query) use ($name) {
                $query->whereHas('employee', function ($query) use ($name) {
                    $query->whereHas('jobApplication', function ($query) use ($name) {
                        $query->whereHas('empData', function ($query) use ($name) {

                            // search after ignoring the case
                            $query->whereRaw('LOWER(first_name) LIKE ?', ["%$name%"])
                                ->orWhereRaw('LOWER(last_name) LIKE ?', ["%$name%"])
                                ->orWhereRaw('CONCAT(LOWER(first_name), " ", LOWER(last_name)) LIKE ?', ["%$name%"]);

                        });
                    });
                });
            });

        }
        return $log->paginate(10);
    }
}
