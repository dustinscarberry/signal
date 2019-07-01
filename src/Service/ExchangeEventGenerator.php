<?php

namespace App\Service;

use jamesiarmes\PhpEws\Client;
use jamesiarmes\PhpEws\Request\CreateItemType;
use jamesiarmes\PhpEws\Request\UpdateItemType;
use jamesiarmes\PhpEws\Request\GetItemType;
use jamesiarmes\PhpEws\ArrayType\NonEmptyArrayOfAllItemsType;
use jamesiarmes\PhpEws\ArrayType\NonEmptyArrayOfItemChangeDescriptionsType;
use jamesiarmes\PhpEws\ArrayType\NonEmptyArrayOfAttendeesType;
use jamesiarmes\PhpEws\Enumeration\BodyTypeType;
use jamesiarmes\PhpEws\Enumeration\CalendarItemCreateOrDeleteOperationType;
use jamesiarmes\PhpEws\Enumeration\ResponseClassType;
use jamesiarmes\PhpEws\Type\BodyType;
use jamesiarmes\PhpEws\Type\CalendarItemType;
use jamesiarmes\PhpEws\Enumeration\DefaultShapeNamesType;
use jamesiarmes\PhpEws\Type\ItemResponseShapeType;
use jamesiarmes\PhpEws\ArrayType\NonEmptyArrayOfBaseItemIdsType;
use jamesiarmes\PhpEws\Enumeration\MessageDispositionType;
use jamesiarmes\PhpEws\Type\CancelCalendarItemType;
use jamesiarmes\PhpEws\Enumeration\CalendarItemUpdateOperationType;
use jamesiarmes\PhpEws\Enumeration\ConflictResolutionType;
use jamesiarmes\PhpEws\Enumeration\UnindexedFieldURIType;
use jamesiarmes\PhpEws\Type\ItemChangeType;
use jamesiarmes\PhpEws\Type\ItemIdType;
use jamesiarmes\PhpEws\Type\SetItemFieldType;
use App\Model\AppConfig;
use App\Entity\Maintenance;

class ExchangeEventGenerator
{
  public static function createEvent(Maintenance $maintenance)
  {
    //convert timestamps into date objects
    $startDate = date('Y-m-d H:i:s', $maintenance->getScheduledFor());
    $endDate = date('Y-m-d H:i:s', $maintenance->getAnticipatedEnd());

    $timezone = new \DateTimeZone('America/New_York');
    $startDate = new \DateTime($startDate, $timezone);
    $endDate = new \DateTime($endDate, $timezone);

    //create event object
    $event = new CalendarItemType();
    $event->RequiredAttendees = new NonEmptyArrayOfAttendeesType();
    $event->Start = $startDate->format('c');
    $event->End = $endDate->format('c');
    $event->Subject = $maintenance->getName();
    $event->Body = new BodyType();
    $event->Body->_ = $maintenance->getPurpose();
    $event->Body->BodyType = BodyTypeType::TEXT;

    //create request object
    $req = new CreateItemType();
    $req->SendMeetingInvitations = CalendarItemCreateOrDeleteOperationType::SEND_ONLY_TO_ALL;
    $req->Items = new NonEmptyArrayOfAllItemsType();
    $req->Items->CalendarItem[] = $event;

    //create client and send request
    $client = self::getClient();
    $rsp = $client->CreateItem($req);

    //parse response to return event id from exchange for storage
    $message = $rsp->ResponseMessages->CreateItemResponseMessage;

    if (!isset($message[0]))
      throw new \Exception('Exchange calendar server could not be contacted');

    $message = $message[0];

    //make sure the request succeeded.
    if ($message->ResponseClass != ResponseClassType::SUCCESS)
      throw new \Exception('Exchange event creation failed');

    //get id for created event
    return $message->Items->CalendarItem[0]->ItemId->Id;
  }

  public static function updateEvent(Maintenance $maintenance, $eventId)
  {
    //convert timestamps into date objects
    $startDate = date('Y-m-d H:i:s', $maintenance->getScheduledFor());
    $endDate = date('Y-m-d H:i:s', $maintenance->getAnticipatedEnd());

    $timezone = new \DateTimeZone('America/New_York');
    $startDate = new \DateTime($startDate, $timezone);
    $endDate = new \DateTime($endDate, $timezone);

    //create updated event object
    $event = new ItemChangeType();
    $event->ItemId = new ItemIdType();
    $event->ItemId->Id = $eventId;
    $event->ItemId->ChangeKey = self::getChangeKey($eventId);
    $event->Updates = new NonEmptyArrayOfItemChangeDescriptionsType();

    // Set the updated start time.
    $field = new SetItemFieldType();
    $field->FieldURI = new PathToUnindexedFieldType();
    $field->FieldURI->FieldURI = UnindexedFieldURIType::CALENDAR_START;
    $field->CalendarItem = new CalendarItemType();
    $field->CalendarItem->Start = $startDate->format('c');
    $event->Updates->SetItemField[] = $field;

    // Set the updated end time.
    $field = new SetItemFieldType();
    $field->FieldURI = new PathToUnindexedFieldType();
    $field->FieldURI->FieldURI = UnindexedFieldURIType::CALENDAR_END;
    $field->CalendarItem = new CalendarItemType();
    $field->CalendarItem->End = $endDate->format('c');
    $event->Updates->SetItemField[] = $field;

    //set updated subject
    $field = new SetItemFieldType();
    $field->FieldURI = new PathToUnindexedFieldType();
    $field->FieldURI->FieldURI = UnindexedFieldURIType::ITEM_SUBJECT;
    $field->CalendarItem = new CalendarItemType();
    $field->CalendarItem->Subject = $maintenance->getName();
    $event->Updates->SetItemField[] = $field;

    //set updated description
    $field = new SetItemFieldType();
    $field->FieldURI = new PathToUnindexedFieldType();
    $field->FieldURI->FieldURI = UnindexedFieldURIType::ITEM_BODY;
    $field->CalendarItem = new CalendarItemType();
    $field->CalendarItem->Body = new BodyType();
    $field->CalendarItem->Body->BodyType = BodyTypeType::TEXT;
    $field->CalendarItem->Body->_ = $maintenance->getPurpose();
    $event->Updates->SetItemField[] = $field;

    //create request object
    $req = new UpdateItemType();
    $req->ConflictResolution = ConflictResolutionType::ALWAYS_OVERWRITE;
    $req->SendMeetingInvitationsOrCancellations = CalendarItemUpdateOperationType::SEND_TO_ALL_AND_SAVE_COPY;
    $req->ItemChanges[] = $event;

    //create client and send request
    $client = self::getClient();
    $rsp = $client->UpdateItem($req);

    //parse response to return event id from exchange for storage
    $message = $rsp->ResponseMessages->UpdateItemResponseMessage;

    if (!isset($message[0]))
      throw new \Exception('Exchange calendar server could not be contacted');

    $message = $message[0];

    //make sure the request succeeded.
    if ($message->ResponseClass != ResponseClassType::SUCCESS)
      throw new \Exception('Exchange event update failed');

    //get id for updated event
    return $message->Items->CalendarItem[0]->ItemId->Id;
  }

  public static function deleteEvent($eventId)
  {
    //create cancelation object
    $cancellation = new CancelCalendarItemType();
    $cancellation->ReferenceItemId = new ItemIdType();
    $cancellation->ReferenceItemId->Id = $eventId;
    $cancellation->ReferenceItemId->ChangeKey = self::getChangeKey($eventId);

    //create request object
    $req = new CreateItemType();
    $req->MessageDisposition = MessageDispositionType::SEND_AND_SAVE_COPY;
    $req->Items = new NonEmptyArrayOfAllItemsType();
    $req->Items->CancelCalendarItem[] = $cancellation;

    //create client and send request
    $client = self::getClient();
    $rsp = $client->CreateItem($req);

    //parse response to delete event from exchange
    $message = $rsp->ResponseMessages->CreateItemResponseMessage;

    if (!isset($message[0]))
      throw new \Exception('Exchange calendar server could not be contacted');

    $message = $message[0];

    //make sure the request succeeded.
    if ($message->ResponseClass != ResponseClassType::SUCCESS)
      throw new \Exception('Exchange event creation failed');

    return true;
  }

  private static function getClient()
  {
    return new Client(
      $_ENV['EXCHANGE_CALENDAR_HOST'],
      $_ENV['EXCHANGE_CALENDAR_USERNAME'],
      $_ENV['EXCHANGE_CALENDAR_PASSWORD'],
      self::getExchangeVersion($_ENV['EXCHANGE_CALENDAR_VERSION'])
    );
  }

  private static function getExchangeVersion($version)
  {
    if ($version == '2013')
      return Client::VERSION_2013;
  }

  private static function getChangeKey($eventId)
  {
    $req = new GetItemType();
    $req->ItemShape = new ItemResponseShapeType();
    $req->ItemShape->BaseShape = DefaultShapeNamesType::ALL_PROPERTIES;
    $req->ItemIds = new NonEmptyArrayOfBaseItemIdsType();

    $item = new ItemIdType();
    $item->Id = $eventId;
    $req->ItemIds->ItemId[] = $item;

    $client = self::getClient();
    $rsp = $client->GetItem($req);

    //parse response to return event change key from exchange
    $message = $rsp->ResponseMessages->GetItemResponseMessage;

    if (!isset($message[0]))
      throw new \Exception('Exchange calendar server could not be contacted');

    $message = $message[0];

    //make sure the request succeeded.
    if ($message->ResponseClass != ResponseClassType::SUCCESS)
      throw new \Exception('Failed to get event from exchange to modify');

    //return event change key
    return $message->Items->CalendarItem[0]->ItemId->ChangeKey;
  }
}
