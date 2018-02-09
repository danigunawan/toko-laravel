<?php

namespace Tests\Unit;

use App\KasMasuk;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class KasMasukTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCrudKasMasuk()
    {
        // untuk proses tambah
        $kas_masuk = KasMasuk::create([
            'kas_id'      => '1',
            'kategori_id' => '1',
            'jumlah'      => '10000',
            'keterangan'  => 'halal',
        ]);

        $this->assertDatabaseHas('kas_masuks', [
            'kas_id'      => '1',
            'kategori_id' => '1',
            'jumlah'      => '10000',
            'keterangan'  => 'halal',
        ]);

        // untuk proses edit
        $kas_masuk_update = KasMasuk::find($kas_masuk->kas_masuk_id);
        $kas_masuk_update->update([
            'kas_id'      => '2',
            'kategori_id' => '2',
            'jumlah'      => '20000',
            'keterangan'  => 'haram']);
        $this->assertDatabaseHas('kas_masuks', [
            'kas_id'      => '2',
            'kategori_id' => '2',
            'jumlah'      => '20000',
            'keterangan'  => 'haram']);

        // untuk hapus
        $hapus_kas_masuk = KasMasuk::destroy($kas_masuk->kas_masuk_id);
        $this->assertEquals('1', $hapus_kas_masuk);
    }

    public function testHTTPTambahUser()
    {
        // Role Login
        $user = User::find(1);

        $response = $this->actingAs($user)->json('POST', route('kas'), [
            "name"        => "khoirul",
            "email"       => "khoirul@gmail.com",
            "password"    => "rahasiaku",
            "is_verified" => "1",
            "otoritas"    => "1",
            "team_id"     => array('0' => 1, '1' => 2),
        ]);

        $response->assertStatus(302)
            ->assertRedirect(route('users.index'));

        $this->get($response->headers->get('location'))->assertSee("<p>Berhasil menyimpan user khoirul</p>");
        // table KasMasuk
        $this->assertDatabaseHas('users', [
            "name"        => "khoirul",
            "email"       => "khoirul@gmail.com",
            "is_verified" => "1",
        ]);
        //  table role_user
        $this->assertDatabaseHas('role_user', [
            "role_id" => "1",
        ]);
        // table team_users
        $this->assertDatabaseHas('team_users', [
            "team_id" => array('0' => 1, '1' => 2),
        ]);
    }

    // halaman edit user
    public function testHTTPEditUser()
    {
        $user = User::find(1);

        $response = $this->actingAs($user)->get(route('users.edit', $user->id));
        $response->assertStatus(200)
            ->assertSee("Ubah Data Member");
    }

    // proses update user
    public function testHTTPUpdateUser()
    {

        $users = KasMasuk::create([
            "name"        => "khoirul",
            "email"       => "khoirul@gmail.com",
            "password"    => "rahasiaku",
            "is_verified" => "1",
        ]);
        //login user -> admin
        $user = KasMasuk::find(1);

        $response = $this->actingAs($user)->json('POST', route('users.update', $users->id), [
            '_method'     => 'PUT',
            "name"        => "irul",
            "email"       => "irul@gmail.com",
            "password"    => "rahasiaku",
            "is_verified" => "1",
            "otoritas"    => "2",
            "team_id"     => array('0' => 1, '1' => 2),
        ]);

        $response->assertStatus(302)
            ->assertRedirect(route('users.index'));

        $response2 = $this->get($response->headers->get('location'))->assertSee("Anda Berhasil mengedit irul !");

    }
    //PROOSES HAPUS USER
    public function testHTTPUser()
    {
        //HAPUS BANK
        $users = KasMasuk::create([
            "name"        => "khoirul",
            "email"       => "khoirul@gmail.com",
            "password"    => "rahasiaku",
            "is_verified" => "1",
        ]);

        //login user -> admin
        $user = KasMasuk::find(1);

        $response = $this->actingAs($user)->json('POST', route('users.destroy', $users->id), ['_method' => 'DELETE']);

        $response->assertStatus(302)
            ->assertRedirect(route('users.index'));

        $response2 = $this->get($response->headers->get('location'))->assertSee("Proses menghapus berhasil !");
    }
}
