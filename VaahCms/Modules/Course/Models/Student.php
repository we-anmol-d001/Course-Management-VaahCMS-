<?php namespace VaahCms\Modules\Course\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Faker\Factory;
use WebReinvent\VaahCms\Models\VaahModel;
use WebReinvent\VaahCms\Traits\CrudWithUuidObservantTrait;
use WebReinvent\VaahCms\Models\User;
use WebReinvent\VaahCms\Libraries\VaahSeeder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use WebReinvent\VaahCms\Models\Taxonomy;
use VaahCms\Modules\Course\Mails\DeleteRecordMail;
use Illuminate\Database\Eloquent\Model;
use WebReinvent\VaahCms\Libraries\VaahMail;
use VaahCms\Modules\Course\Mails\StudentRegisteredMail;
use VaahCms\Modules\Course\Traits\SendEmailDeleteTrait;

class Student extends VaahModel
{

    use SoftDeletes;
    use CrudWithUuidObservantTrait;
    use SendEmailDeleteTrait;


    //-------------------------------------------------
    protected $table = 'co_students';
    //-------------------------------------------------
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    //-------------------------------------------------
    protected $fillable = [
        'uuid',
        'name',
        'email',
        'dob',
        'gender',
        'slug',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    //-------------------------------------------------
    protected $fill_except = [

    ];

    //-------------------------------------------------
    protected $appends = [
        'course_count'
    ];

    //-------------------------------------------------
    //Accessor to add the field in the table of course
     public function courseCount(): Attribute
    {
    
        return Attribute::make(
            get: fn() => $this->courses->count(),
        );
    }

    
    // for relationship between the courses and student.
    public function courses()
    {
    return $this->belongsToMany(Course::class,'co_course_co_student','co_student_id','co_course_id');
    }
    public function gender()
    {
        return $this->belongsTo(Taxonomy::class,
            'gender', 'id'
        );
    }

    //-------------------------------------------------
    protected function serializeDate(DateTimeInterface $date)
    {
        $date_time_format = config('settings.global.datetime_format');
        return $date->format($date_time_format);
    }

    //-------------------------------------------------
    public static function getUnFillableColumns()
    {
        return [
            'uuid',
            'created_by',
            'updated_by',
            'deleted_by',
        ];
    }
    //-------------------------------------------------
    public static function getFillableColumns()
    {
        $model = new self();
        $except = $model->fill_except;
        $fillable_columns = $model->getFillable();
        $fillable_columns = array_diff(
            $fillable_columns, $except
        );
        return $fillable_columns;
    }
    //-------------------------------------------------
    public static function getEmptyItem()
    {
        $model = new self();
        $fillable = $model->getFillable();
        $empty_item = [];
        foreach ($fillable as $column)
        {
            $empty_item[$column] = null;
        }

        $empty_item['is_active'] = 1;

        return $empty_item;
    }

    //-------------------------------------------------

    public function createdByUser()
    {
        return $this->belongsTo(User::class,
            'created_by', 'id'
        )->select('id', 'uuid', 'first_name', 'last_name', 'email');
    }

    //-------------------------------------------------
    public function updatedByUser()
    {
        return $this->belongsTo(User::class,
            'updated_by', 'id'
        )->select('id', 'uuid', 'first_name', 'last_name', 'email');
    }

    //-------------------------------------------------
    public function deletedByUser()
    {
        return $this->belongsTo(User::class,
            'deleted_by', 'id'
        )->select('id', 'uuid', 'first_name', 'last_name', 'email');
    }

    //-------------------------------------------------
    public function getTableColumns()
    {
        return $this->getConnection()->getSchemaBuilder()
            ->getColumnListing($this->getTable());
    }

    //-------------------------------------------------
    public function scopeExclude($query, $columns)
    {
        return $query->select(array_diff($this->getTableColumns(), $columns));
    }

    //-------------------------------------------------
    public function scopeBetweenDates($query, $from, $to)
    {

        if ($from) {
            $from = \Carbon::parse($from)
                ->startOfDay()
                ->toDateTimeString();
        }

        if ($to) {
            $to = \Carbon::parse($to)
                ->endOfDay()
                ->toDateTimeString();
        }

        $query->whereBetween('updated_at', [$from, $to]);
    }

    //-------------------------------------------------
    public static function createItem($request)
    {

        $inputs = $request->all();

        $validation = self::validation($inputs);
        if (!$validation['success']) {
            return $validation;
        }


        // check if name exist
        $item = self::where('name', $inputs['name'])->withTrashed()->first();

        if ($item) {
            $error_message = "This name is already exist".($item->deleted_at?' in trash.':'.');
            $response['success'] = false;
            $response['errors'][] = $error_message;
            return $response;
        }

        // check if slug exist
        $item = self::where('slug', $inputs['slug'])->withTrashed()->first();

        if ($item) {
            $error_message = "This slug is already exist".($item->deleted_at?' in trash.':'.');
            $response['success'] = false;
            $response['errors'][] = $error_message;
            return $response;
        }

        //Implementing many to many relationship between courses and student table
        $course_ids = $inputs['courses'];
        
        $item = new self();
        $item->fill($inputs);
        
       
        $item->save();
        $item->courses()->attach($course_ids);

        // Email sending logic------
        $item->load('courses');
        $mail = new StudentRegisteredMail($item); 
        $to = [$item->email];
        
        // For sending the email
        // VaahMail::send($mail, $to); 

       

        $response = self::getItem($item->id);
        $response['messages'][] = trans("vaahcms-general.saved_successfully");
        return $response;

    }

    //-------------------------------------------------
    public function scopeGetSorted($query, $filter)
    {

        if(!isset($filter['sort']))
        {
            return $query->orderBy('id', 'desc');
        }

        $sort = $filter['sort'];


        $direction = Str::contains($sort, ':');

        if(!$direction)
        {
            return $query->orderBy($sort, 'asc');
        }

        $sort = explode(':', $sort);

        return $query->orderBy($sort[0], $sort[1]);
    }
    //-------------------------------------------------
    public function scopeIsActiveFilter($query, $filter)
    {

        if(!isset($filter['is_active'])
            || is_null($filter['is_active'])
            || $filter['is_active'] === 'null'
        )
        {
            return $query;
        }
        $is_active = $filter['is_active'];

        if($is_active === 'true' || $is_active === true)
        {
            return $query->where('is_active', 1);
        } else{
            return $query->where(function ($q){
                $q->whereNull('is_active')
                    ->orWhere('is_active', 0);
            });
        }

    }
    //-------------------------------------------------
    public function scopeTrashedFilter($query, $filter)
    {

        if(!isset($filter['trashed']))
        {
            return $query;
        }
        $trashed = $filter['trashed'];

        if($trashed === 'include')
        {
            return $query->withTrashed();
        } else if($trashed === 'only'){
            return $query->onlyTrashed();
        }

    }
    //-------------------------------------------------
    public function scopeSearchFilter($query, $filter)
    {

        if(!isset($filter['q']))
        {
            return $query;
        }
        $search_array = explode(' ',$filter['q']);
        foreach ($search_array as $search_item){
            $query->where(function ($q1) use ($search_item) {
                $q1->where('name', 'LIKE', '%' . $search_item . '%')
                    ->orWhere('slug', 'LIKE', '%' . $search_item . '%')
                    ->orWhere('id', 'LIKE', $search_item . '%')
                    ->orWhere('email', 'LIKE', $search_item . '%')
                    ->orWhere('gender', 'LIKE', $search_item . '%')
                    ->orWhere('dob', 'LIKE', $search_item . '%');
            });
        }

    }
    //-------------------------------------------------
    public function scopeEnrolledStudent($query, $filter)
    {
        if (empty($filter['course']) || !is_array($filter['course'])) {
            return $query;
        }

        return $query->whereHas('courses', function ($q) use ($filter) {
            $q->whereIn('co_course_id', $filter['course']);
        });
    }

    public function scopeFilterByDob($query, $filter)
    {
        
        if (!empty($filter['dob_from']) && !empty($filter['dob_to'])) {
            return $query->whereBetween('dob', [$filter['dob_from'], $filter['dob_to']]);
        }

        if (!empty($filter['dob_from'])) {
            return $query->where('dob', '>=', $filter['dob_from']);
        }

        if (!empty($filter['dob_to'])) {
            return $query->where('dob', '<=', $filter['dob_to']);
        }

        return $query;
    }

    public function scopeCourseRange($query, $filter)
    {
        if (isset($filter['course_count_min']) && isset($filter['course_count_max'])) {
            $max = $filter['course_count_max'];
            $min = $filter['course_count_min'];

            return $query->withCount('courses')
            ->having('courses_count', '>=', $min)
            ->having('courses_count', '<=', $max);
        }

        return $query;
    }

    public function scopeStudentRecordByFilter($query,$filter)
    {
        if (isset($filter['course_uuid'])) {
            $id = $filter['course_uuid'];
    
            return $query->whereHas('courses', function($q1) use ($id) {
                $q1->where('co_courses.uuid', $id);
            } );
        }

        return $query;
    }

    //-------------------------------------------------
    public static function getList($request)
    {
        
        $list = self::getSorted($request->filter)->with('gender');
        $list->isActiveFilter($request->filter);
        $list->trashedFilter($request->filter);
        $list->searchFilter($request->filter);
        $list->enrolledStudent($request->filter);
        $list->filterByDob($request->filter);
        $list->courseRange($request->filter);
        $list->studentRecordByFilter($request->filter);
        
        
        $reloaded = $request->query('reloading');
    
        $rows = config('vaahcms.per_page');

        if($request->has('rows'))
        {
            $rows = $request->rows;
        }

        $list = $list->paginate($rows);

        $response['success'] = true;
        $response['data'] = $list;
        if($reloaded)
        {
       
        
        $response['messages'][] = trans("Page loaded...");
        }
        return $response;


    }

    //-------------------------------------------------
    public static function updateList($request)
    {

        $inputs = $request->all();

        $rules = array(
            'type' => 'required',
        );

        $messages = array(
            'type.required' => trans("vaahcms-general.action_type_is_required"),
        );


        $validator = \Validator::make($inputs, $rules, $messages);
        if ($validator->fails()) {

            $errors = errorsToArray($validator->errors());
            $response['success'] = false;
            $response['errors'] = $errors;
            return $response;
        }

        if(isset($inputs['items']))
        {
            $items_id = collect($inputs['items'])
                ->pluck('id')
                ->toArray();
        }

        $items = self::whereIn('id', $items_id);

        switch ($inputs['type']) {
            case 'deactivate':
                $items->withTrashed()->where(['is_active' => 1])
                    ->update(['is_active' => null]);
                break;
            case 'activate':
                $items->withTrashed()->where(function ($q){
                    $q->where('is_active', 0)->orWhereNull('is_active');
                })->update(['is_active' => 1]);
                break;
            case 'trash':
                 
                $records = self::whereIn('id', $items_id)->get();
                $records->each->delete();
                self::recordDeleted($records);
                break;
            case 'restore':
                self::whereIn('id', $items_id)->onlyTrashed()
                    ->get()->each->restore();
                break;
        }

        $response['success'] = true;
        $response['data'] = true;
        $response['messages'][] = trans("vaahcms-general.action_successful");

        return $response;
    }

    //-------------------------------------------------
    public static function deleteList($request): array
    {
        
        $inputs = $request->all();
        
        $rules = array(
            'type' => 'required',
            'items' => 'required',
        );

        $messages = array(
            'type.required' => trans("vaahcms-general.action_type_is_required"),
            'items.required' => trans("vaahcms-general.select_items"),
        );

        $validator = \Validator::make($inputs, $rules, $messages);
        if ($validator->fails()) {

            $errors = errorsToArray($validator->errors());
            $response['success'] = false;
            $response['errors'] = $errors;
            return $response;
        }

        $items_id = collect($inputs['items'])->pluck('id')->toArray();

        $students = self::whereIn('id', $items_id)->get();
        foreach ($students as $student) {
        $student->courses()->detach(); 
        }

        self::whereIn('id', $items_id)->forceDelete();

        $response['success'] = true;
        $response['data'] = true;
        $response['messages'][] = trans("vaahcms-general.action_successful");

        return $response;
    }
    //-------------------------------------------------
    
    //-------------------------------------------------
     public static function listAction($request, $type): array
    {

        $list = self::query();

        if($request->has('filter')){
            $list->getSorted($request->filter);
            $list->isActiveFilter($request->filter);
            $list->trashedFilter($request->filter);
            $list->searchFilter($request->filter);
        }
        
        switch ($type) {
            case 'activate-all':
                $list->withTrashed()->where(function ($q){
                    $q->where('is_active', 0)->orWhereNull('is_active');
                })->update(['is_active' => 1]);
                break;
            case 'deactivate-all':
                $list->withTrashed()->where(['is_active' => 1])
                    ->update(['is_active' => null]);
                break;
            case 'trash-all':
                $records=$list->get();
                
                if ($records->isEmpty()) {
                 break; 
                 }
                $records->each->delete();
                self::recordDeleted($records);
                
                break;
            case 'restore-all':
                $list->onlyTrashed()->get()
                    ->each->restore();
                break;
            case 'delete-all':
                $students = $list->get();
                foreach ($students as $student) {
                    $student->courses()->detach(); 
                }
                $list->forceDelete();
                break;
            case 'create-100-records':
            case 'create-1000-records':
            case 'create-5000-records':
            case 'create-10000-records':

            if(!config('course.is_dev')){
                $response['success'] = false;
                $response['errors'][] = 'User is not in the development environment.';

                return $response;
            }

            preg_match('/-(.*?)-/', $type, $matches);

            if(count($matches) !== 2){
                break;
            }

            self::seedSampleItems($matches[1]);
            break;
        }

        $response['success'] = true;
        $response['data'] = true;
        $response['messages'][] = trans("vaahcms-general.action_successful");

        return $response;
    }
    //-------------------------------------------------
    public static function getItem($id)
    {

        $item = self::where('id', $id)
            ->with(['createdByUser', 'updatedByUser', 'deletedByUser'])
            ->with('courses')
            ->withTrashed()
            ->first();

        // dd($item);    
        if(!$item)
        {
            $response['success'] = false;
            $response['errors'][] = 'Record not found with ID: '.$id;
            return $response;
        }
        $response['success'] = true;
        $response['data'] = $item;

        return $response;

    }
    //-------------------------------------------------
    public static function updateItem($request, $id)
    {
        $inputs = $request->all();

        $validation = self::validation($inputs);
        if (!$validation['success']) {
            return $validation;
        }

        $item = self::where('id', '!=', $id)
            ->withTrashed()
            ->where('name', $inputs['name'])->first();

         if ($item) {
             $error_message = "This name is already exist".($item->deleted_at?' in trash.':'.');
             $response['success'] = false;
             $response['errors'][] = $error_message;
             return $response;
         }

         // check if slug exist
         $item = self::where('id', '!=', $id)
             ->withTrashed()
             ->where('slug', $inputs['slug'])->first();

         if ($item) {
             $error_message = "This slug is already exist".($item->deleted_at?' in trash.':'.');
             $response['success'] = false;
             $response['errors'][] = $error_message;
             return $response;
         }

        $item = self::where('id', $id)->withTrashed()->first();
        $item->fill($inputs);
        
        $course_ids = $inputs['courses'];
    

        $item->save();
        $item->courses()->sync($course_ids);

        
        $response = self::getItem($item->id);
        $response['messages'][] = trans("vaahcms-general.saved_successfully");
        return $response;

    }
    //-------------------------------------------------
    public static function deleteItem($request, $id): array
    {
      
        if (!$item) {
            $response['success'] = false;
            $response['errors'][] = trans("vaahcms-general.record_does_not_exist");
            return $response;
        }
        
        $items_id = [$item->id];


        $students = self::whereIn('id', $items_id)->get();
        foreach ($students as $student) {
        $student->courses()->detach(); 
        }
        $item->forceDelete();

        $response['success'] = true;
        $response['data'] = [];
        $response['messages'][] = trans("vaahcms-general.record_has_been_deleted");

        return $response;
    }
    //-------------------------------------------------
    public static function itemAction($request, $id, $type): array
    {
        switch($type)
        {
            case 'activate':
                self::where('id', $id)
                    ->withTrashed()
                    ->update(['is_active' => 1]);
                break;
            case 'deactivate':
                self::where('id', $id)
                    ->withTrashed()
                    ->update(['is_active' => null]);
                break;
            case 'trash':
                $record=self::find($id);
                $record->delete();
                self::recordDeleted($record);
                
                break;
            case 'restore':
                self::where('id', $id)
                    ->onlyTrashed()
                    ->first()->restore();
                break;
        }

        return self::getItem($id);
    }
    //-------------------------------------------------

    public static function validation($inputs)
    {

        $rules = array(
            'name' => 'required|max:150',
            'slug' => 'required|max:150',
            'email'=> 'required|email',

        );


        $validator = \Validator::make($inputs, $rules);
        if ($validator->fails()) {
            $messages = $validator->errors();
            $response['success'] = false;
            $response['errors'] = $messages->all();
            return $response;
        }

        $response['success'] = true;
        return $response;

    }

    //-------------------------------------------------
    public static function getActiveItems()
    {
        $item = self::where('is_active', 1)
            ->withTrashed()
            ->first();
        return $item;
    }

    //-------------------------------------------------
    //-------------------------------------------------
    public static function seedSampleItems($records=100)
    {

        $i = 0;

        while($i < $records)
        {
            $inputs = self::fillItem(false);

            $item =  new self();
            $item->fill($inputs);
            $item->save();
            $item->courses()->attach($inputs['courses']);
            $i++;

        }

    }
 
    // Custom Utility Methods
    public static function getGender(){
        $genders = Taxonomy::getTaxonomyByType('gender');

        return $genders->pluck('id')->toArray();
    }

    //-------------------------------------------------
    public static function fillItem($is_response_return = true)
    {
        $request = new Request([
            'model_namespace' => self::class,
            'except' => self::getUnFillableColumns()
        ]);
        
        $fillable = VaahSeeder::fill($request);
    
        if(!$fillable['success']){
            return $fillable;
        }
        $inputs = $fillable['data']['fill'];

        $faker = Factory::create();

        /*
         * You can override the filled variables below this line.
         * You should also return relationship from here
         */
        $name = $faker->name;
        $inputs['name']=$name;
        $inputs['slug'] = \Str::slug($name);

        $course_ids= Course::pluck('id')->toArray();      
        $max_count = count($course_ids);
        $count = min(2, $max_count);
        $inputs['courses'] =  $count > 0 ? $faker->randomElements($course_ids, $count) : [];

        $inputs['email'] = $faker->email;
         $inputs['gender'] = $faker->randomElement(self::getGender());
        $inputs['dob']=$faker->date();

        if(!$is_response_return){
            return $inputs;
        }

      

        $response['success'] = true;
        $response['data']['fill'] = $inputs;
        return $response;
    }

    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------

   
}
