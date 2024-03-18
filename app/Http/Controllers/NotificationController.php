<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
/**
 * @OA\Schema(
 *     schema="Notification",
 *     title="Notification",
 *     description="Notification model",
 *     @OA\Property(
 *         property="message_id",
 *         type="integer",
 *         description="ID of the message associated with the notification"
 *     ),
 *     @OA\Property(
 *         property="message_description",
 *         type="string",
 *         description="Description of the message"
 *     ),
 *     @OA\Property(
 *         property="sender_msg",
 *         type="string",
 *         description="Sender of the message"
 *     ),
 *     @OA\Property(
 *         property="receptient_msg",
 *         type="string",
 *         description="Recipient of the message"
 *     ),
 * )
 */

class NotificationController extends Controller
{
  /**
 * @OA\Get(
 *      path="/api/Notification/index",
 *      operationId="getNotifications",
 *      tags={"Notifications"},
 *      summary="Get all notifications",
 *      description="Retrieves all notifications from the database",
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/Notification")
 *          ),
 *      ),
 * )
 */
  public function index(){
    $notification= Notification::get();
  return response()->json($notification, 200);
  }
/**
 * @OA\Get(
 *      path="/api/Notification/ShowNotificationsByReceptientId/{ReceptientId}",
 *      operationId="getNotificationsByReceptientId",
 *      tags={"Notifications"},
 *      summary="Get notifications by Recipient ID",
 *      description="Retrieves notifications associated with a specific Recipient by their ID",
 *      @OA\Parameter(
 *          name="ReceptientId",
 *          in="path",
 *          description="ID of the Recipient",
 *          required=true,
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/Notification")
 *          ),
 *      ),
 * )
 */
  public function ShowNotificationsByReceptientId($ReceptientId)
  {
      $notification = Notification::where('receptient_msg', $ReceptientId)->get();
      return response()->json($notification, 200);
  }


/**
 * @OA\Get(
 *      path="/api/Notification/show/{id}",
 *      operationId="getNotificationById",
 *      tags={"Notifications"},
 *      summary="Get a notification by ID",
 *      description="Retrieves a notification by its ID",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the Notification",
 *          required=true,
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(ref="#/components/schemas/Notification"),
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Not Found",
 *          @OA\JsonContent(
 *              type="string",
 *              example="Notification not found"
 *          ),
 *      ),
 * )
 */
     public function show($id){
      $notification = Notification::find($id);
      if($notification){
    return response()->json($notification, 200);

      }else{
        $msg="votre id n'est pas trouve";
            return response()->json($msg, 200);



      }   }
}
