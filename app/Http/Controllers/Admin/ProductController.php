<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class ProductController
 * @package App\Http\Controllers\Admin
 *
 * Handles Product management functionalities in the admin panel.
 */
class ProductController extends Controller
{
    /**
     * Display the Product index view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
       
        $sessionUser = auth()->user();
        if($sessionUser->hasPermission('admin/product')){
            return view('admin/product/index');
        }else{
          return redirect('admin/dashboard')->with('error', 'You are not authorized');
        }
    }

    /**
     * Get a list of Products.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
     
        return response()->json((new Product())->list($request->all()));
    }

    /**
     * Show the form for creating a new Product.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin/product/create');
    }

    /**
     * Show the form for updating a specific Product.
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {     
        $model = Product::find($request->id);
        $Product = auth()->user();
        $permission = explode(',', $Product->permission);
        if ($Product->type == 1 && !in_array('admin/Product/update', $permission)) {
            return redirect('admin/product')->with('error', 'No permission To Update Product');
        }

        return view('admin/product/update', compact('permission', 'model'));
    }

    /**
     * Save or update Product data.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Request $request)
    {
        return response()->json((new Product())->store($request->all()));
    }

    /**
     * View a specific Product's details along with logs and devices.
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function view(Request $request)
    {
        $id = $request->input('id');
        $model = Product::where('id',$id)->first();
        return view('admin/product/view', compact('model'));
    }

    /**
     * Delete a specific Product.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request)
    {
        $model = Product::find($request->input('id'));
        if (!$model) {
            return response()->json(['status' => 0, 'message' => 'No data found']);
        }
        $model->delete();
        return response()->json(['status' => 1, 'message' => 'Product deleted successfully.', 'next' => 'refresh']);
    }

    /**
     * Change the status of a Product.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => $validator->errors()->first()]);
        }

        $id = $request->input('id');
        $model = Product::find($id);

        if (!$model) {
            return response()->json(['status' => 0, 'message' => 'Product not found']);
        }

        $model->update(['status' => !$model->status]); 
        return response()->json(['status' => 1, 'message' => 'Product status updated successfully.', 'next' => 'refresh']);
    }

   public function productExportAll(Request $request)
    {
        $products = Product::all();
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="products.csv"');
        $output = fopen('php://output', 'w');

        $headers = ['title','image', 'description', 'amount', 'status'];
        fputcsv($output, $headers);

        foreach ($products as $product) {
            fputcsv($output, [
                $product->title,
                $product->image,
                $product->description,
                $product->amount,
                $product->status  ? 'Active' : 'Inactive'
            ]);
        }
        fclose($output);
        exit;
    }

}