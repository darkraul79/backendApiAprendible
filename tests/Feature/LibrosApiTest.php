<?php


namespace Tests\Unit;

use App\Models\Libro;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LibrosApiTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    function puedo_leer_todos_libros()
    {
        $libros = Libro::factory(4)->create();

        $this->getJson(route('libros.index'))
            ->assertJsonFragment([
                'titulo' => $libros[0]->titulo
            ]);
        // dd($response);
    }

    /** @test */
    public function puedo_obtener_un_libro()
    {
        // assertions

        $libro = Libro::factory()->create();

        $this->getJson(route('libros.show', $libro))
            ->assertJsonFragment([
                'titulo' => $libro->titulo
            ]);
    }


    /** @test */
    public function puedo_crear_libros()
    {
        // assertions
        $this->postJson(route('libros.store'), [])->assertJsonValidationErrorFor('titulo');

        $this->postJson(route('libros.store'), [
            'titulo' => "nuevo libro"
        ])->assertJsonFragment([
            'titulo' => "nuevo libro"
        ]);

        $this->assertDatabaseHas('libros', [

            'titulo' => "nuevo libro"
        ]);
    }


    /** @test */
    public function puedo_actualizar_libros()
    {
        $libro = Libro::factory()->create();


        $this->patchJson(route('libros.update', $libro), [])->assertJsonValidationErrorFor('titulo');

        $this->patchJson(route('libros.update', $libro), [
            'titulo' => 'Libro actualizado'
        ])->assertJsonFragment([
            'titulo' => 'Libro actualizado'
        ]);
        // assertions



        $this->assertDatabaseHas('libros', [

            'titulo' => "Libro actualizado"
        ]);
    }


    /** @test */
    public function puedo_eliminar_libros()
    {
        // assertions

        $libro = Libro::factory()->create();

        $this->deleteJson(route('libros.destroy', $libro))->assertNoContent();

        $this->assertDatabaseMissing('libros', [
            'titulo' => $libro->titulo
        ])->assertDatabaseCount('libros', 0);
    }
}
