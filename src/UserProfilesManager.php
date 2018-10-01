<?php  declare(strict_types=1);

namespace Bolt\Extension\UserProfile;

use Bolt\Storage\Database\Connection;

class UserProfilesManager
{
    private $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function updateProfile(string $avatar, string $description, int $id)
    {
        $query = "UPDATE bolt_users SET avatar = :avatar, description = :description WHERE id = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue('avatar', $avatar);
        $stmt->bindValue('description', $description);
        $stmt->bindValue('id', $id);
        $stmt->execute();
    }
}
