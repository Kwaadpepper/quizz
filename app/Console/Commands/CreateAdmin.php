<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a backend admin user';

    /**
     * The name of the new user.
     *
     * @var string
     */
    protected $name;

    /**
     * The email of the new user.
     *
     * @var string
     */
    protected $email;

    /**
     * The password of the new user.
     *
     * @var string
     */
    protected $password;


    /**
     * Execute the console command.
     *
     * @return integer
     */
    public function handle(): int
    {
        $this->name = null;
        $this->addName();

        $this->email = null;
        $this->addEmail();

        $this->password = null;
        $this->addPassword();

        $user = new User([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
        ]);
        $user->saveOrFail();

        $this->info('User created 👌');

        return 0;
    }

    /**
     * Add a name to the user.
     *
     * @return void
     */
    private function addName(): void
    {
        while (is_null($this->name)) {
            try {
                $tmp       = $this->ask('Type the wanted user name', 'Saïtama');
                $validator = Validator::make(['name' => $tmp], [
                    'name' => 'required|min:3|max:255'
                ]);
                $validator->validate();
                $this->name = $tmp;
            } catch (ValidationException $e) {
                $this->error(sprintf(
                    'Please provide a valid name %s',
                    \implode(',', collect($e->errors())->flatten()->all())
                ));
                continue;
            } //end try
        }; //end while
    }

    /**
     * Add an email to the user.
     *
     * @return void
     */
    private function addEmail(): void
    {
        while (is_null($this->email)) {
            try {
                $tmp       = $this->ask('Type the wanted user email', 'equipe.web@ldc.fr');
                $validator = Validator::make(['email' => $tmp], [
                    'email' => 'required|unique:users,email|email:rfc,dns'
                ]);
                $validator->validate();
                $this->email = $tmp;
            } catch (ValidationException $e) {
                $this->error(sprintf(
                    'Please provide a valid email %s',
                    \implode(',', collect($e->errors())->flatten()->all())
                ));
                continue;
            } //end try
        }; //end while
    }

    /**
     * Add a password to the user.
     *
     * @return void
     */
    private function addPassword(): void
    {
        while (
            ($this->password = $this->secret(
                (!\is_null($this->password) ? 'Failed to confirm, ' : '') . 'Type the wanted user password'
            )) !== $this->secret('Confirm the password')
        ) {
            continue;
        }; //end while
    }
}
