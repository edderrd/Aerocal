<?php

/**
 * Messages
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Message extends BaseMessage
{

    /**
     * Lookup for unreaded messages for a user
     * @param int $userId
     * @return array
     */
    public static function findUnreadByUserId($userId)
    {
        return Doctrine_Query::create()
                    ->from("Message m")
                    ->leftJoin("m.FromUser fu")
                    ->leftJoin("m.ToUser tu")
                    ->addWhere("m.to_user_id = $userId")
                    ->addWhere("m.is_read = false")
                    ->fetchArray(true);
    }

    /**
     * Create a message to all admins
     * @param int $fromUser
     * @param string $subject
     * @param string $msg
     */
    public static function notifyAllAdmins($fromUser, $subject, $msg)
    {
        $admins = User::getAllAdmins();

        if (!empty($admins))
        {
            foreach($admins as $admin)
            {
                $message = new Message();
                $message->from_user_id = $fromUser;
                $message->to_user_id = $admin['id'];
                $message->subject = $subject;
                $message->content = $msg;
                $message->is_read = false;
                $message->created_on = date("Y-m-d G:i:s", time());
                $message->save();
            }
        }
    }
    
    /**
     * Get all messages from a user from the most recent created_on
     * @param int $userId
     * @return array
     */
    public static function findAllByUserId($userId)
    {
    	if (!empty($userId))
    	{
    		$r = Doctrine_Query::create()
    				->from(__CLASS__ . " m")
    				->leftJoin("m.FromUser fu")
                    ->leftJoin("m.ToUser tu")
                    ->addWhere("m.to_user_id = $userId")
                    ->orderBy("created_on desc")
                    ->fetchArray(true);
            
        	return $r;
    	}
    	return false;
    }
    
    /**
     * Mark a message as read
     * @param int $messageId
     * @retun mixed
     */
    public static function markRead($messageId)
    {
    	if (!empty($messageId))
    	{
    		$r = Doctrine_Query::create()
    				->update(__CLASS__ . " m")
    				->set("is_read", true)
    				->where("m.id = $messageId")
    				->execute();
    		return $r;
    	}
    	
    	return false;
    }

    /**
     * Delete a message by his id
     * @param intr $id
     * @return int
     */
    public function deleteById($id)
    {
        return Doctrine_Query::create()
                ->delete(__CLASS__ . " m")
                ->where("m.id = $id")
                ->execute();
    }

}