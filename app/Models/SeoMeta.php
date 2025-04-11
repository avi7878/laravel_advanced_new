<?php

namespace App\Models;

use App\Helpers\Pagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use App\Helpers\General;

/**
 * Class SeoMeta
 *
 * Model for managing SEO metadata in the application.
 *
 * @package App\Models
 */
class SeoMeta extends Model
{
    protected $table = 'seo_meta';
    public $timestamps = false;
    protected $fillable = ['url', 'title', 'keyword', 'description'];

    /**
     * Retrieve meta tags for the current route's URL.
     *
     * @return array The meta tags for the current route's URL.
     */
    public function getMetaData(): ?array
    {
        $currentUrl = Route::current()?->getName();
        if (!$currentUrl) {
            return null;
        }

        $cacheKey = 'seo_meta_' . $currentUrl;
        $metaData = Cache::get($cacheKey);

        if ($metaData) {
            return $metaData;
        }
        $siteMeta = self::where('url', $currentUrl)->first();
        if ($siteMeta) {
            $metaData = [
                'title' => $siteMeta->title,
                'keyword' => $siteMeta->keyword,
                'description' => $siteMeta->description,
            ];
        }else{
            $metaData =[
                'title' => '',
                'keyword' => '',
                'description' => '',
            ];
        }
        Cache::put($cacheKey, $metaData, 86400); // Cache for 1 day
        return $metaData;
    }

    /**
     * Get the HTML badge for status.
     *
     * @param int $status Status value (1 for Active, 0 for Inactive).
     * @return string HTML badge for the status.
     */
    public function getStatusBadge(int $status): string
    {
        return $status === 1
            ? '<span class="badge rounded-pill bg-label-success">Active</span>'
            : '<span class="badge rounded-pill bg-label-danger">Inactive</span>';
    }

    /**
     * Retrieve active status records from the SEO meta table.
     *
     * @return \Illuminate\Support\Collection Collection of active records.
     */
    public function getActiveStatus(): \Illuminate\Support\Collection
    {
        return DB::table($this->table)->where('sitemap_enable', 1)->get();
    }

    /**
     * Retrieve a paginated list of admin SEO metadata records.
     *
     * @param array $postData Data from the request, typically for pagination and search.
     * @return array Paginated list of SEO metadata with action buttons.
     */
    public function listAdmin(array $postData): array
    {
        $query = DB::table($this->table);
        $searchText = $postData['search']['value'] ?? '';

        if (strlen($searchText) > 2) {
            $query->where("title", 'like', '%' . $searchText . '%');
        }

        $pagination = new Pagination();
        $result = $pagination->getDataTable($query, $postData);

        foreach ($result['data'] as $row) {
            $row->action = sprintf(
                '<a href="page/%s" class="text-body" title="View"><i class="fa fa-eye"></i></a>&nbsp;' .
                '<a href="admin/page/update?id=%d" class="btn btn-info" title="Update"><i class="fa fa-edit"></i></a>',
                e($row->slug),
                $row->id
            );
        }

        return $result;
    }

    /**
     * Retrieve a paginated list of SEO metadata records with search and filter options.
     *
     * @param array $postData Data from the request, typically for pagination and search.
     * @return array Paginated list of SEO metadata with action buttons and status badges.
     */
    public function Seometalist(array $postData): array
    {
        $query = DB::table($this->table)->select('*');
        $searchText = $postData['search']['value'] ?? '';

        if (strlen($searchText) > 2) {
            $query->where(function ($query) use ($searchText) {
                $searchPattern = '%' . $searchText . '%';
                $query->where("title", 'like', $searchPattern)
                      ->orWhere("keyword", 'like', $searchPattern)
                      ->orWhere("url", 'like', $searchPattern)
                      ->orWhere("description", 'like', $searchPattern)
                      ->orWhere("sitemap_enable", 'like', $searchPattern);
            });
        }

        $pagination = new Pagination();
        $result = $pagination->getDataTable($query, $postData);
        $sessionUser = auth()->user();

        foreach ($result['data'] as $row) {
            $row->sitemap_enable = $this->getStatusBadge((int)$row->sitemap_enable);
            $row->action = '';

            if ($sessionUser->hasPermission('admin/seo/update')) {
                $row->action .= sprintf(
                    '<a href="admin/seo/update?id=%d" class="text-body pjax" title="Update"><i class="ti ti-edit ti-sm me-2"></i></a>&nbsp;',
                    $row->id
                );
            }

            if ($sessionUser->hasPermission('admin/seo/delete')) {
                $row->action .= sprintf(
                    '<button style="border:none; background:none;" onclick="app.confirmAction(this);" data-action="admin/seo/delete?id=%d"  class="text-body" title="Delete"><i class="ti ti-trash ti-sm mx-2"></i></button>',
                    $row->id
                );
            }
        }

        return $result;
    }
    public function store(array $postData)
    {
        $validator = Validator::make($postData, [
            'title' => 'required',
            'url' => 'required',
            'keyword' => 'required',
            'description' => 'required',
        ]);
        if ($validator->fails()) {
            return [
                'status' => 0,
                'message' => $validator->errors()->first(),
            ];
        }
        $id = $postData['id'] ?? null;
        $seometa = $id ? self::find($id) : new self();
        if ($id && !$seometa) {
            return ['status' => 0, 'message' => 'Seo meta not found.'];
        }
        // Prepare tags
    
        // Slugify and assign values
        $general = new General();
        $seometa->url = $postData['url'];
        $seometa->title = $postData['title'];
        $seometa->keyword = $postData['keyword'];
        $seometa->description = $postData['description'];
        $seometa->sitemap_enable = $postData['site_map'];
        $seometa->last_modified = $postData['last_modified'];
        $seometa->change_frequency = $postData['frequency'];
        $seometa->priority = $postData['priority'];
        // Handle main image
    

  
        return $seometa->save()
            ? ['status' => 1, 'message' => 'Seo Saved successfully', 'next' => 'load', 'url' => 'admin/seo/meta']
            : ['status' => 0, 'message' => 'Failed to save the Seo Meta.'];
    }






}
