<div class="space-y-5">
    
    {{-- Input NIDN --}}
    <div>
        <label for="nidn" class="block text-sm font-semibold text-gray-700 mb-1">
            NIDN <span class="text-red-500">*</span>
        </label>
        <div class="relative rounded-lg shadow-sm">
            <input type="text" name="nidn" id="nidn" 
                   value="{{ old('nidn', $dosen->nidn ?? '') }}" 
                   placeholder="Contoh: 0712345678" 
                   required
                   maxlength="15"
                   class="block w-full rounded-lg pl-3 pr-3 py-2.5 text-sm placeholder-gray-400 focus:outline-none focus:ring-2 
                    {{ $errors->has('nidn') 
                        ? 'border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500' 
                        : 'border-gray-300 text-gray-900 focus:border-blue-500 focus:ring-blue-500' }}">
        </div>
        @error('nidn')
            <p class="mt-1.5 text-xs text-red-600 font-medium flex items-center gap-1">
                <span>⚠️</span> {{ $message }}
            </p>
        @enderror
    </div>

    {{-- Input Nama Lengkap --}}
    <div>
        <label for="nama" class="block text-sm font-semibold text-gray-700 mb-1">
            Nama Lengkap <span class="text-red-500">*</span>
        </label>
        <div class="relative rounded-lg shadow-sm">
            <input type="text" name="nama" id="nama" 
                   value="{{ old('nama', $dosen->nama ?? '') }}" 
                   placeholder="Masukkan nama lengkap beserta gelar" 
                   required
                   class="block w-full rounded-lg pl-3 pr-3 py-2.5 text-sm placeholder-gray-400 focus:outline-none focus:ring-2 
                    {{ $errors->has('nama') 
                        ? 'border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500' 
                        : 'border-gray-300 text-gray-900 focus:border-blue-500 focus:ring-blue-500' }}">
        </div>
        @error('nama')
            <p class="mt-1.5 text-xs text-red-600 font-medium flex items-center gap-1">
                <span>⚠️</span> {{ $message }}
            </p>
        @enderror
    </div>

    {{-- Input Jabatan Fungsional --}}
    <div>
        <label for="jabatan_fungsional" class="block text-sm font-semibold text-gray-700 mb-1">
            Jabatan Fungsional <span class="text-red-500">*</span>
        </label>
        <div class="relative rounded-lg shadow-sm">
            <select name="jabatan_fungsional" id="jabatan_fungsional" required
                    class="block w-full rounded-lg pl-3 pr-10 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 
                    {{ $errors->has('jabatan_fungsional') 
                        ? 'border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500' 
                        : 'border-gray-300 text-gray-900 focus:border-blue-500 focus:ring-blue-500' }}">
                <option value="" disabled {{ old('jabatan_fungsional', $dosen->jabatan_fungsional ?? '') == '' ? 'selected' : '' }}>-- Pilih Jabatan Fungsional --</option>
                
                @foreach(['Asisten Ahli', 'Lektor', 'Kepala Lektor', 'Profesor'] as $jabatan)
                    <option value="{{ $jabatan }}" {{ old('jabatan_fungsional', $dosen->jabatan_fungsional ?? '') == $jabatan ? 'selected' : '' }}>
                        {{ $jabatan }}
                    </option>
                @endforeach
            </select>
        </div>
        @error('jabatan_fungsional')
            <p class="mt-1.5 text-xs text-red-600 font-medium flex items-center gap-1">
                <span>⚠️</span> {{ $message }}
            </p>
        @enderror
    </div>

</div>