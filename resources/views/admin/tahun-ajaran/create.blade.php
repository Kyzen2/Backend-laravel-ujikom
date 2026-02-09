<x-admin-layout>
    <div class="p-6 max-w-xl">
        <h1 class="text-xl font-bold mb-4">Tambah Tahun Ajaran</h1>

        <form action="/admin/tahun-ajaran" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block mb-1">Tahun</label>
                <input type="text"
                    name="tahun"
                    class="w-full border rounded p-2"
                    placeholder="2024/2025">
            </div>

            <div class="mb-4">
                <label class="block mb-1">Semester</label>
                <select name="semester" class="w-full border rounded p-2">
                    <option value="">Pilih</option>
                    <option value="ganjil">Ganjil</option>
                    <option value="genap">Genap</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-1">Status</label>
                <select name="status" class="w-full border rounded p-2">
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div>

            <button type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded">
                Simpan
            </button>
        </form>
    </div>
</x-admin-layout>